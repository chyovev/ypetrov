<?php

namespace App\Admin\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeleteRequest extends FormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    public function authorize(): bool {
        return true;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    public function rules(): array {
        return [
            'ban' => ['sometimes', 'required', 'accepted'],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * NB! Keep in mind that the Comment model implements the SoftDeletes
     *     trait, so the comment won't actually be removed from the database;
     *     it will simply be marked as having been deleted.
     */
    public function process(): void {
        $this->comment->delete();

        if ($this->query('ban')) {
            $this->comment->visitor->markAsBanned();
        }
    }

}
