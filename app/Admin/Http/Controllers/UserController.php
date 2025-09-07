<?php

namespace App\Admin\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Admin\Http\Requests\Users\DeleteRequest;
use App\Admin\Http\Requests\Users\StoreRequest;
use App\Admin\Http\Requests\Users\UpdateRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request) {
        $query = User::query();

        if ( ! is_null($request->query('search'))) {
            $query->filterBy($request->query('search'));
        }

        return view('admin.users.index', [
            'users' => $query->paginate(20),
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
    public function destroy(DeleteRequest $request, User $user) {
        $request->process();

        return redirect()
            ->back()
            ->withSuccess(__('global.delete_successful'));
    }

}
