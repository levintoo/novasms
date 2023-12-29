<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * * Determine if the user is authorized to make this request.
     * */
    public function authorize(): bool
    {

        $user = User::query()

            ->select('email','id')

            ->where('email',$this->email)

            ->firstOrFail();

        $currentRoles = $user->getRoleNames();

        $admin = \Auth::user();

        if ($admin->email === $this->email) {
            return false;
        }
        elseif ($admin->hasRole('super admin')) {
            return true;
        }
        elseif ($currentRoles->doesntContain('super admin') && $this->role !== 'super admin') {
            return true;
        }
        else {
            return false;
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','max:255','string'],

            'email' => ['required','max:255','string'],

            'role' => ['required', Rule::exists('roles','name')],
        ];
    }
}
