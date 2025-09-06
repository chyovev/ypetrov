<?php

namespace App\Admin\Http\Requests\Auth;

use Exception;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ForgotPasswordRequest extends FormRequest
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
     * NB! There's no need to use the 'exists' rule since the underlying
     *     password broker will automatically perform that check.
     */
    public function rules(): array {
        return [
            'email' => ['required', 'email'],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Try to send a password reset link to the user.
     * Under normal circumstances the response from the password broker
     * should be RESET_LINK_SENT. In any other case (e.g. no such user,
     * too many requests, etc.), the broker would return another string
     * which should be used as a validation error.
     * 
     * NB! If an actual exception gets thrown during the sending of
     *     the reset link (such as no connection to SMTP server),
     *     it should be caught and reported, and the end user should
     *     be served a generic error message.
     * 
     * @throws ValidationException
     */
    public function sendResetLink(): void {
        try {
            $response = Password::sendResetLink(
                $this->only('email')
            );
        }
        catch (Exception $e) {
            report($e);
            $response = 'global.internal_error';
        }

        if ($response !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['email' => __($response)]);
        }
    }

}
