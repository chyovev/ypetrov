<?php

namespace App\Admin\Http\Requests\PressArticles;

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
            'slug'         => ['required', 'max:255', 'regex:/^[a-z0-9\-]+$/i', Rule::unique('press_articles')->ignore($this->press_article)],
            'press'        => ['required', 'max:255'],
            'publish_date' => ['sometimes', 'nullable', 'date_format:d.m.Y.'],
            'text'         => ['required', 'max:65535'],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Provide a meaningful error message for the slug regex validation.
     */
    public function messages(): array {
        return [
            'slug.regex' => 'The :attribute field must only contain letters, numbers and hyphens.',
        ]; 
    }

}
