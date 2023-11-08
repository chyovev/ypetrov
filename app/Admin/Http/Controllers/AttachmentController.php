<?php

namespace App\Admin\Http\Controllers;

use App\Models\Attachment;
use App\Http\Controllers\Controller;

class AttachmentController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Delete a single attachment record.
     * 
     * NB! Keep in mind that there's an attachment observer which takes
     *     care of deleting the actual file once the record gets deleted,
     *     but also may prevent the deletion in case it would disrupt
     *     the attachable object's attachment settings.
     * 
     * @see \App\Observers\AttachmentObserver
     */
    public function destroy(Attachment $attachment) {
        $attachment->delete();

        return redirect()
            ->back()
            ->withSuccess('Attachment successfully deleted!');
    }

}
