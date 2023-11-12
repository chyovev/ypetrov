<?php

namespace App\Admin\Http\Controllers;

use App\Models\PressArticle;
use App\Admin\Http\Requests\PressArticles\FormRequest;
use App\Http\Controllers\Controller;

class PressArticleController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $query = PressArticle::query()
            ->withCount(['attachments', 'comments'])
            ->orderBy('order');

        return view('admin.press_articles.index', [
            'pressArticles' => $query->paginate(20),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('admin.press_articles.form', ['pressArticle' => new PressArticle()]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Store a newly created resource in storage.
     */
    public function store(FormRequest $request) {
        $data = $request->validated();

        $pressArticle = PressArticle::create($data);

        return redirect()
            ->route('admin.press_articles.edit', ['press_article' => $pressArticle])
            ->withSuccess('Press article successfully created!');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PressArticle $pressArticle) {
        return view('admin.press_articles.form', ['pressArticle' => $pressArticle]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, PressArticle $pressArticle) {
        $data = $request->validated();

        $pressArticle->update($data);

        return redirect()
            ->route('admin.press_articles.edit', ['press_article' => $pressArticle])
            ->withSuccess('Press article successfully updated!');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove the specified resource from storage.
     * 
     * NB! Keep in mind that PressArticle objects have polymorphic relationships
     *     which cannot have cascade deletion in MySQL. To work around this
     *     problem, the PressArticle class implements polymorphic interfaces which
     *     have registered delete observers.
     */
    public function destroy(PressArticle $pressArticle) {
        $pressArticle->delete();

        return redirect()
            ->back()
            ->withSuccess('Press article successfully deleted!');
    }

}
