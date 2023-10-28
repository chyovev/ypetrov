<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\Poems\FormRequest;
use App\Models\Poem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoemController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return view('admin.poems.index', [
            'poems' => Poem::withCount('books')->paginate(20),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('admin.poems.form', ['poem' => new Poem()]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created resource in storage.
     */
    public function store(FormRequest $request) {
        $data = $request->validated();

        $poem = Poem::create($data);

        return redirect()
            ->route('admin.poems.edit', ['poem' => $poem])
            ->withSuccess('User successfully created!');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poem $poem) {
        return view('admin.poems.form', ['poem' => $poem]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, Poem $poem) {
        $data = $request->validated();

        $poem->update($data);

        return redirect()
            ->route('admin.poems.edit', ['poem' => $poem])
            ->withSuccess('Poem successfully updated!');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove the specified resource from storage.
     * 
     * NB! Keep in mind that Poem objects have polymorphic relationships
     *     which cannot have cascade deletion in MySQL. To work around this
     *     problem, the Poem class implements polymorphic interfaces which
     *     have registered delete observers.
     */
    public function destroy(Poem $poem) {
        $poem->delete();

        return redirect()
            ->back()
            ->withSuccess('Contact message successfully deleted!');
    }

}
