<?php

namespace App\Admin\Http\Controllers;

use App\Models\StaticPage;
use App\Admin\Http\Requests\StaticPages\FormRequest;
use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaticPage $staticPage) {
        return view('admin.static_pages.form', ['staticPage' => $staticPage]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, StaticPage $staticPage) {
        $data = $request->validated();

        $staticPage->update($data);

        return redirect()
            ->route('admin.static_pages.edit', ['static_page' => $staticPage])
            ->withSuccess(__('global.edit_successful'));
    }

}
