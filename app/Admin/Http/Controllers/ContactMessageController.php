<?php

namespace App\Admin\Http\Controllers;

use App\Models\ContactMessage;
use App\Http\Controllers\Controller;

class ContactMessageController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index() {
        // fetch contacts together with visitors
        // in order to display the visitor's country
        $query = ContactMessage::latest('id')->with('visitor');
        
        return view('admin.contact_messages.index', [
            'messages' => $query->paginate(20),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contactMessage) {
        $contactMessage->markAsRead();
        
        return view('admin.contact_messages.show', [
            'message' => $contactMessage,
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage) {
        $contactMessage->delete();

        return redirect()
            ->back()
            ->withSuccess(__('global.delete_successful'));
    }

}
