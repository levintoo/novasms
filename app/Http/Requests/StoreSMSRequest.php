<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreSMSRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'recipients' => 'in:one,group',
            'group' => ['required_if:recipients,group','max:255',Rule::exists('groups','id')->where('user_id',Auth::id())],
            'phone' => 'required_if:recipients,one|max:255',
            'message' => 'required|string',
        ];
    }
}
