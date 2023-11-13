<?php

namespace App\Admin\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordRequest extends FormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Always allow the forgot password request to go through.
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
            'token' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                PasswordRule::defaults(),
                'confirmed',
            ],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Try to reset the user's password.
     * Under normal circumstances the response from the password broker
     * should be PASSWORD_RESET. In any other case (e.g. no such user,
     * token has expired, etc.), the broker would return another string
     * which should be used as a validation error.
     * 
     * @throws ValidationException
     * @return void
     */
    public function resetPassword(): void {
        $credentials = $this->validated();
        $callback    = function (User $user, string $password) {
            $user->resetPassword($password);
            
            // automatically mark the user as logged in
            auth('admin')->login($user);
        };

        $response = Password::reset($credentials, $callback);

        if ($response !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => __($response)]);
        }
    }

}
