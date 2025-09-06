<?php

namespace App\Admin\Http\Requests\Videos;

use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;
use Illuminate\Validation\Rule;

/**
 * The FormRequest class is used as validation
 * on both new and existing videos.
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
            'slug'         => ['required', 'max:255', new Slug, Rule::unique('videos')->ignore($this->video)],
            'publish_date' => ['sometimes', 'nullable', 'date_format:d.m.Y.'],
            'summary'      => ['sometimes', 'max:500'],
        ]; 
    }

}
