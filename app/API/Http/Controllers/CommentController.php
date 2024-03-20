<?php

namespace App\API\Http\Controllers;

use App\API\Http\Requests\CommentRequest;
use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Response;

class CommentController extends Controller
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
        $request->createComment($visitor);
        
        return response()->ok(['message' => __('global.comment_added')], Response::HTTP_CREATED);
    }
    
}