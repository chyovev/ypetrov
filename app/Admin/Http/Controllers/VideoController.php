<?php

namespace App\Admin\Http\Controllers;

use App\Models\Video;
use App\Admin\Http\Requests\Videos\FormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;

class VideoController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index(HttpFormRequest $request) {
        $query = Video::query()
            ->withCount(['attachments', 'comments'])
            ->orderBy('order');

        if ( ! is_null($request->query('search'))) {
            $query->filterBy($request->query('search'));
        }

        return view('admin.videos.index', [
            'videos' => $query->paginate(20),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('admin.videos.form', ['video' => new Video()]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created resource in storage.
     */
    public function store(FormRequest $request) {
        $data = $request->validated();

        $video = Video::create($data);

        return redirect()
            ->route('admin.videos.edit', ['video' => $video])
            ->withSuccess(__('global.creation_successful'));
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video) {
        return view('admin.videos.form', ['video' => $video]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, Video $video) {
        $data = $request->validated();

        $video->update($data);

        return redirect()
            ->route('admin.videos.edit', ['video' => $video])
            ->withSuccess(__('global.edit_successful'));
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove the specified resource from storage.
     * 
     * NB! Keep in mind that Video objects have polymorphic relationships
     *     which cannot have cascade deletion in MySQL. To work around this
     *     problem, the Video class implements polymorphic interfaces which
     *     have registered delete observers.
     */
    public function destroy(Video $video) {
        $video->delete();

        return redirect()
            ->back()
            ->withSuccess(__('global.delete_successful'));
    }

}
