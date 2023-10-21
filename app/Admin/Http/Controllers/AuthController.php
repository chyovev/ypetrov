<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Admin\Http\Requests\LoginRequest;

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
     * Endpoint to log out the currently logged in user
     * and redirect them back to the log in page.
     */
    public function logout(): RedirectResponse {
        auth()->logout();

        return redirect()->route('admin.login');
    }

}