<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->role !== 'super admin' || \Auth::user()->hasRole('super admin')) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users', 'max:254'],
            'role' => ['required', Rule::exists('roles','name')],
        ];
    }
}
