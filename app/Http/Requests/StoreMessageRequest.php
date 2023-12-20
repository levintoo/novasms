<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'recipient' => ['in:contact,group'],

            'phone' => ['required_if:recipient,contact', 'nullable', 'string', 'max_digits:9',],

            'group' => ['required_if:recipient,group', 'nullable', Rule::exists('groups', 'id')->where('user_id', Auth::id()),],

            'message' => ['required'],

        ];
    }
}
