<?php

namespace App\Admin\Http\Requests\Users;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    public function rules(): array {
        return [
            'name'     => ['required', 'max:255'],
            'email'    => ['required', 'max:255', 'email', Rule::unique('users')],
            'password' => ['required', 'nullable', Password::defaults()],
        ];
    }

}
