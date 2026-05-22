<?php

namespace App\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderRequest extends FormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    public function rules(): array {
        return [
            'id'   => ['required', 'array'],
            'id.*' => ['distinct:strict', "exists:{$this->table},id"],
        ];
    }

}
