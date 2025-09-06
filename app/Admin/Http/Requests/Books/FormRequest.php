<?php

namespace App\Admin\Http\Requests\Books;

use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;
use Illuminate\Validation\Rule;

/**
 * The FormRequest class is used as validation
 * on both new and existing books.
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
            'slug'         => ['required', 'max:255', new Slug, Rule::unique('books')->ignore($this->book)],
            'publisher'    => ['sometimes', 'max:255'],
            'publish_year' => ['sometimes', 'nullable', 'date_format:Y'],
            'poem_id'      => ['required', 'array', 'min:1'],
            'poem_id.*'    => ['distinct', 'exists:poems,id'],
        ];
    }

}
