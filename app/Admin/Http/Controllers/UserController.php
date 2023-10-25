<?php

namespace App\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return view('admin.users.index', [
            'users' => User::paginate(20),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) {
        if ($this->isTryingToDeleteSelf($user)) {
            return back()->withErrors('You cannot delete yourself!');
        }

        $user->delete();

        return redirect()
            ->back()
            ->withSuccess('User successfully deleted!');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if the currently logged in user is trying to delete themselves,
     * i.e. if the UserPolicy allows them to carry out a delete action.
     * 
     * NB! Usually policy checks are done using the authorize() method
     *     inside the controllers, but this would throw a 403 (Forbidden)
     *     exception, and we actually want to redirect the user back to
     *     the previous page with an error flash message.
     * 
     * @see \App\Policies\UserPolicy 
     * 
     * @param  User $user
     * @return bool
     */
    private function isTryingToDeleteSelf(User $user): bool {
        return ( ! auth()->user()->can('delete', $user));
    }

}
