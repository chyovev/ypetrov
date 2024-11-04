<?php

namespace App\Admin\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeleteRequest extends FormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check with the user policy if the source user (the one
     * doing the deletion) can actually delete the target user
     * (the one passed as parameter).
     * 
     * @return bool
     */
    public function authorize(): bool {
        $source = $this->user();
        $target = $this->user;

        return $source->can('delete', $target);
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * In case the authorization fails, redirect the user back
     * to the previous page with the respective error message.
     * 
     * @throws HttpResponseException
     * @return void
     */
    protected function failedAuthorization(): void {
        throw new HttpResponseException(back()->withErrors(__('global.cannot_delete_self')));
    }

    ///////////////////////////////////////////////////////////////////////////
    public function process(): void {
        $this->user->delete();
    }

}
