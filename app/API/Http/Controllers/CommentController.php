<?php

namespace App\API\Http\Controllers;

use App\API\Http\Requests\CommentRequest;
use App\Models\Visitor;
use Illuminate\Http\Response;

class CommentController extends AbstractController
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Create a comment for a commentable object and associate it
     * with the visitor which has been registered through the
     * RegisterVisitor middleware.
     * 
     * @param  CommentRequest $request
     * @param  Visitor $visitor
     * @return Response
     */
    public function create(CommentRequest $request, Visitor $visitor) {
        $comment = $request->createComment($visitor);

        $response = [
            'message' => __('global.comment_added'),
            'html'    => $comment->asHtml(),
        ];
        
        return response()->ok($response, Response::HTTP_CREATED);
    }
    
}