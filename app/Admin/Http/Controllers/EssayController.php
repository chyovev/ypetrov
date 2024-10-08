<?php

namespace App\Admin\Http\Controllers;

use App\Models\Essay;
use App\Admin\Http\Requests\FilterRequest;
use App\Admin\Http\Requests\Essays\FormRequest;
use App\Http\Controllers\Controller;

class EssayController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request) {
        $query = Essay::query()
            ->withCount(['attachments', 'comments'])
            ->orderBy('order');

        $request->addOptionalFilterToQuery($query, ['title', 'text']);

        return view('admin.essays.index', [
            'essays' => $query->paginate(20),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('admin.essays.form', ['essay' => new Essay()]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created resource in storage.
     */
    public function store(FormRequest $request) {
        $data = $request->validated();

        $essay = Essay::create($data);

        return redirect()
            ->route('admin.essays.edit', ['essay' => $essay])
            ->withSuccess(__('global.creation_successful'));
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Essay $essay) {
        return view('admin.essays.form', ['essay' => $essay]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, Essay $essay) {
        $data = $request->validated();

        $essay->update($data);

        return redirect()
            ->route('admin.essays.edit', ['essay' => $essay])
            ->withSuccess(__('global.edit_successful'));
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove the specified resource from storage.
     * 
     * NB! Keep in mind that Essay objects have polymorphic relationships
     *     which cannot have cascade deletion in MySQL. To work around this
     *     problem, the Essay class implements polymorphic interfaces which
     *     have registered delete observers.
     */
    public function destroy(Essay $essay) {
        $essay->delete();

        return redirect()
            ->back()
            ->withSuccess(__('global.delete_successful'));
    }

}
