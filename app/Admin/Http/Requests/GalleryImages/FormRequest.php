<?php

namespace App\Admin\Http\Requests\GalleryImages;

use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;

/**
 * The FormRequest class is used as validation
 * on both new and existing gallery images.
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
            'max:255',
        ];
    }

}
