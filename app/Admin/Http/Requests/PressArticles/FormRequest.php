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
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\\Rule|array|string>
     */
    public function rules(): array {
        return [
            'is_active' => $this->getIsActiveRules(),
            'title'     => $this->getTitleRules(),
            'slug'      => $this->getSlugRules(),
            'text'      => $this->getTextRules(),
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

    ///////////////////////////////////////////////////////////////////////////
    private function getIsActiveRules(): array {
        return [
            'required',
            'boolean',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getTitleRules(): array {
        return [
            'required',
            'max:255',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getSlugRules(): array {
        return [
            'required',
            'regex:/^[a-z0-9\-]+$/i',
            'max:255',
            Rule::unique('press_articles')->ignore($this->press_article),
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getTextRules(): array {
        return [
            'required',
            'max:65535',
        ];
    }

}
