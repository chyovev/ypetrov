<?php

namespace App\Admin\Http\Controllers;

use App\Models\Comment;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Delete a single comment record.
     * 
     * NB! Keep in mind that the Comment model implements the SoftDeletes
     *     trait, so the comment won't actually be removed from the database;
     *     it will simply be marked as having been deleted.
     */
    public function destroy(Comment $comment) {
        $comment->delete();

        return redirect()
            ->back()
            ->withSuccess('Comment successfully deleted!');
    }

}
