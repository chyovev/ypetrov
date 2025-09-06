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
    public function rules(): array {
        return [
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The password reset link sent to the user contains the token
     * as a query parameter. If the user deletes it manually (unlikely),
     * the token's required validation rule can be a bit user-friendlier.
     * 
     * @return array<string,string>
     */
    public function messages(): array {
        return [
            'token.required' => __('global.missing_token'),
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

        // if a validation error has occurred,
        // display it under the proper field
        if ($response !== Password::PASSWORD_RESET) {
            $field = ($response === Password::INVALID_USER) ? 'email' : 'token';

            throw ValidationException::withMessages([$field => __($response)]);
        }
    }

}
