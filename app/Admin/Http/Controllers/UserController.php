<?php

namespace App\Admin\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Admin\Http\Requests\Users\StoreRequest;
use App\Admin\Http\Requests\Users\UpdateRequest;

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
        return view('admin.users.form', ['user' => new User()]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request) {
        $data = $request->validated();

        $user = User::create($data);

        return redirect()
            ->route('admin.users.edit', ['user' => $user])
            ->withSuccess(__('global.creation_successful'));
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) {
        return view('admin.users.form', ['user' => $user]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user) {
        $data = $request->validated();

        $user->update($data);

        return redirect()
            ->route('admin.users.edit', ['user' => $user])
            ->withSuccess(__('global.edit_successful'));
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) {
        if ($this->isTryingToDeleteSelf($user)) {
            return back()->withErrors(__('global.cannot_delete_self'));
        }

        $user->delete();

        return redirect()
            ->back()
            ->withSuccess(__('global.delete_successful'));
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
