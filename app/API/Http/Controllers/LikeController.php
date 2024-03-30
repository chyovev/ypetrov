<?php

namespace App\API\Http\Controllers;

use App\API\Http\Requests\LikeRequest;
use App\Models\Visitor;
use Illuminate\Http\Response;

class LikeController extends AbstractController
{

    ///////////////////////////////////////////////////////////////////////////
    /** 
     * Register a like by a visitor.
     * 
     * @param  LikeRequest $request
     * @param  Visitor $visitor
     * @return Response
     */
    public function like(LikeRequest $request, Visitor $visitor) {
        $request->like($visitor);
        
        return response()->ok(['message' => __('global.like_registered')], Response::HTTP_CREATED);
    }

    ///////////////////////////////////////////////////////////////////////////
    /** 
     * Revoke a like previously given by a visitor.
     * 
     * @param  LikeRequest $request
     * @param  Visitor $visitor
     * @return Response
     */
    public function revoke_like(LikeRequest $request, Visitor $visitor) {
        $request->revokeLike($visitor);

        return response()->empty();
    }
}