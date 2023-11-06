<?php

namespace App\Admin\Http\Requests\Videos;

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
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {
        return [
            'is_active'    => $this->getIsActiveRules(),
            'title'        => $this->getTitleRules(),
            'slug'         => $this->getSlugRules(),
            'publish_date' => $this->getPublishDateRules(),
            'summary'      => $this->getSummaryRules(),

            // TODO: move to a neutral place for all attachable objects
            'attachments'   => $this->getAttachmentsRules(),
            'attachments.*' => $this->getAttachmentsElementsRules(),
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
            Rule::unique('videos')->ignore($this->video),
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getPublishDateRules(): array {
        return [
            'sometimes',
            'nullable',
            'date_format:d.m.Y.',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getSummaryRules(): array {
        return [
            'sometimes',
            'max:500',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getAttachmentsRules(): array {
        return [
            'required',
            'array',
            'min:1',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getAttachmentsElementsRules(): array {
        return [
            'file',
        ];
    }

}
