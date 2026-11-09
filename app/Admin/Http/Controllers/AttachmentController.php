<?php

namespace App\Admin\Http\Controllers;

use App\Models\Attachment;

class AttachmentController
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Delete a single attachment record.
     * 
     * NB! Keep in mind that there's an event listener
     *     deleting the actual physical file.
     */
    public function destroy(Attachment $attachment) {
        $attachment->delete();

        return redirect()
            ->back()
            ->withSuccess('Attachment successfully deleted!');
    }

}
