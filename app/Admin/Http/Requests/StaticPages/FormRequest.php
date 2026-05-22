<?php

namespace App\Admin\Http\Requests\StaticPages;

use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;

/**
 * The FormRequest class is used as validation
 * when updating existing static pages.
 */

class FormRequest extends HttpFormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    public function rules(): array {
        return [
            'title' => ['required', 'max:255'],
            'text'  => ['required', 'max:65535'],
        ];
    }

}
