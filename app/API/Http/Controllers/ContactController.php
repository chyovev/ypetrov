<?php

namespace App\API\Http\Controllers;

use App\API\Http\Requests\ContactRequest;
use App\Models\Visitor;
use Illuminate\Http\Response;

class ContactController extends AbstractController
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Create a contact message and associate it with the visitor
     * which has been registered throug the RegisterVisitor middleware.
     * 
     * @param  ContactRequest $request
     * @param  Visitor $visitor
     * @return Response
     */
    public function create_contact_message(ContactRequest $request, Visitor $visitor) {
        $request->createContactMessage($visitor);
        
        return response()->ok(['message' => __('global.contact_message_sent')], Response::HTTP_CREATED);
    }
    
}