<?php

namespace App\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Always allow the login request to go through.
     * 
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,\Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array {
        return [
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'optional|boolean',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Try to authenticate a user by their email and password.
     * 
     * @return bool
     */
    public function authenticate(): bool {
        $credentials = $this->only('email', 'password');
        $remember    = $this->boolean('remember');

        if (auth('admin')->attempt($credentials, $remember)) {
            $this->session()->regenerate();

            return true;
        }

        return false;
    }
}
