<?php

namespace App\Admin\Http\Requests\PressArticles;

use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;
use Illuminate\Validation\Rule;

/**
 * The FormRequest class is used as validation
 * on both new and existing press_articles.
 */

class FormRequest extends HttpFormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Always allow the store request to go through.
     * 
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function rules(): array {
        return [
            'is_active'    => ['required', 'boolean'],
            'title'        => ['required', 'max:255'],
            'slug'         => ['required', 'max:255', new Slug, Rule::unique('press_articles')->ignore($this->press_article)],
            'press'        => ['required', 'max:255'],
            'publish_date' => ['sometimes', 'nullable', 'date_format:d.m.Y.'],
            'text'         => ['required', 'max:65535'],
        ]; 
    }

}
