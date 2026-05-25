<?php

namespace App\Admin\Http\Controllers;

use App\Models\ContactMessage;
use App\Utils\Breadcrumbs\BreadcrumbManager;
use Illuminate\Foundation\Http\FormRequest;

class ContactMessageController
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private BreadcrumbManager $breadcrumbManager) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request) {
        // fetch contacts together with visitors
        // in order to display the visitor's country
        $query = ContactMessage::latest('id')->with('visitor');

        if ( ! is_null($request->query('search'))) {
            $query->filterBy($request->query('search'));
        }

        return view('admin.contact_messages.index', [
            'messages'    => $query->paginate(20),
            'breadcrumbs' => $this->breadcrumbManager->getCrumbs('contact_messages.index'),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contactMessage) {
        $contactMessage->markAsRead();
        
        return view('admin.contact_messages.show', [
            'message'     => $contactMessage,
            'breadcrumbs' => $this->breadcrumbManager->getCrumbs('contact_messages.show', $contactMessage),
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
