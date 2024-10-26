<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest {
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'email' => 'required|string|email|max:255|exists:accounts,email',
            'password' => 'required|string'
        ];
    }

    public function messages(): array {
        return [
            'email.email' => 'Please enter valid e-mail.',
            'email.max' => 'Email entered must be less than 255 characters.',
            'email.exists' => 'Email entered is not registered to the system.'
        ];
    }
}
