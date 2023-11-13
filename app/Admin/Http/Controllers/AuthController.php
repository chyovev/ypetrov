<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Admin\Http\Requests\Auth\LoginRequest;
use App\Admin\Http\Requests\Auth\ForgotPasswordRequest;

class AuthController
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Endpoint to try process a login request and authenticate a user.
     * If the authentication is successful, the user should be redirected
     * to the homepage, otherwise they should be given a vague error feedback.
     * 
     * @param  LoginRequest $request – data validation & authentication declared there
     * @return RedirectResponse
     */
    public function process_login_request(LoginRequest $request): RedirectResponse {
        if ($request->authenticate()) {
            return redirect()->route('admin.home');
        }

        return back()->withErrors(['email' => 'Email or password wrong'])->onlyInput('email');;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Send a reset link to the user who has forgotten their password.
     * 
     * @param  ForgotPasswordRequest $request – data validation
     * @return RedirectResponse
     */
    public function process_forgot_password_request(ForgotPasswordRequest $request) {
        $request->sendResetLink();

        return redirect()
            ->back()
            ->withSuccess(__('passwords.sent'));
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Endpoint to log out the currently logged in user
     * and redirect them back to the log in page.
     */
    public function logout(): RedirectResponse {
        auth()->logout();

        return redirect()->route('admin.login');
    }

}