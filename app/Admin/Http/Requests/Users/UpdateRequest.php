<?php

namespace App\Admin\Http\Requests\Users;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends FormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Always allow the store request to go through.
     * 
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    public function rules(): array {
        return [
            'name'     => ['required', 'max:255'],
            'email'    => ['required', 'max:255', 'email', Rule::unique('users')->ignore($this->user)],
            'password' => ['sometimes', 'nullable', Password::defaults()],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Even though the password is marked as optional, it's still
     * part of the validated data set having a null value (due to
     * the conversion middleware).
     * In such cases, the password should be removed from the
     * request data set altogether using the prepareForValidation()
     * method.
     * 
     * @see \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull
     */
    protected function prepareForValidation(): void {
        if (is_null($this->password)) {
            $this->request->remove('password');
        }
    }

}
