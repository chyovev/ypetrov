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
            'title' => $this->getTitleRules(),
            'text'  => $this->getTextRules(),
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
    private function getTextRules(): array {
        return [
            'required',
            'max:65535',
        ];
    }

}
