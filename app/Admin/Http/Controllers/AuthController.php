<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use App\Admin\Http\Requests\Auth\LoginRequest;
use App\Admin\Http\Requests\Auth\ForgotPasswordRequest;
use App\Admin\Http\Requests\Auth\ResetPasswordRequest;

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

        return back()->withErrors(['email' => __('passwords.wrong')])->onlyInput('email');;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Send a reset link to the user who has forgotten their password.
     * 
     * @param  ForgotPasswordRequest $request – data validation
     * @return RedirectResponse
     */
    public function process_forgot_password_request(ForgotPasswordRequest $request): RedirectResponse {
        $request->sendResetLink();

        return redirect()
            ->back()
            ->withSuccess(__('passwords.sent'));
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * The reset password link which was sent to the user
     * should contain the reset token and the user's email
     * as query parameters.
     * Said parameters should be passed to the view, so that
     * it can forward them to the processing reset password
     * request.
     * 
     * @param  Request $request
     * @return View
     */
    public function show_reset_password_form(Request $request): View {
        $data = [
            'email' => $request->input('email'),
            'token' => $request->input('token'),
        ];

        return view('admin.auth.reset_password', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once the reset password request has been validated
     * and the user's password has been reset, they should
     * be redirected to the home page.
     * 
     * @param  ResetPasswordRequest $request – token. user & data validation
     * @return RedirectResponse
     */
    public function process_reset_password_request(ResetPasswordRequest $request): RedirectResponse {
        $request->resetPassword();

        return redirect()
            ->route('admin.home')
            ->withSuccess(__('passwords.reset'));
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