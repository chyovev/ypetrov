<?php

namespace App\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderRequest extends FormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Always allow the reorder request to go through.
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
            'id'   => $this->getIdRules(),
            'id.*' => $this->getIdElementsRules(),
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The id property is a required array.
     * 
     * @return array<int,string>
     */
    private function getIdRules(): array {
        return [
            'required',
            'array',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each of the ID's passed in the request must be distinct and
     * correspond with an existing record from the respective table.
     * 
     * @return array<int,string>
     */
    private function getIdElementsRules(): array {
        return [
            'distinct:strict',
            "exists:{$this->table},id",
        ];
    }

}
