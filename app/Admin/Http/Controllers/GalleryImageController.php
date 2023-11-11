<?php

namespace App\Admin\Http\Controllers;

use App\Models\GalleryImage;
use App\Admin\Http\Requests\GalleryImages\FormRequest;
use App\Http\Controllers\Controller;

class GalleryImageController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return view('admin.gallery_images.index', [
            'galleryImages' => GalleryImage::orderBy('order')->paginate(20),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('admin.gallery_images.form', ['galleryImage' => new GalleryImage()]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created resource in storage.
     */
    public function store(FormRequest $request) {
        $data = $request->validated();

        $galleryImage = GalleryImage::create($data);

        return redirect()
            ->route('admin.gallery_images.edit', ['gallery_image' => $galleryImage])
            ->withSuccess('GalleryImage successfully created!');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GalleryImage $galleryImage) {
        return view('admin.gallery_images.form', ['galleryImage' => $galleryImage]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, GalleryImage $galleryImage) {
        $data = $request->validated();

        $galleryImage->update($data);

        return redirect()
            ->route('admin.gallery_images.edit', ['gallery_image' => $galleryImage])
            ->withSuccess('Image successfully updated!');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove the specified resource from storage.
     * 
     * NB! Keep in mind that GalleryImage objects have polymorphic relationships
     *     which cannot have cascade deletion in MySQL. To work around this
     *     problem, the GalleryImage class implements polymorphic interfaces which
     *     have registered delete observers.
     */
    public function destroy(GalleryImage $galleryImage) {
        $galleryImage->delete();

        return redirect()
            ->back()
            ->withSuccess('Image successfully deleted!');
    }

}
