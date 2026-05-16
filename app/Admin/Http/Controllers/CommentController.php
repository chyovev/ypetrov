<?php

namespace App\Admin\Http\Controllers;

use App\Models\Comment;
use App\Admin\Http\Requests\Comments\DeleteRequest;

class CommentController
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Delete a single comment record.
     */
    public function destroy(DeleteRequest $request, Comment $comment) {
        $request->process();

        return redirect()
            ->back()
            ->withSuccess('Comment successfully deleted!');
    }

}
