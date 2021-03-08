<?php

use Exceptions\ValidationException;

class ContactController extends AppController {

    public $models = ['ContactMessage'];

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        // try to save an ajax post request message
        $this->saveContactMessage();

        $this->loadCaptcha();

        $vars = ['metaTitle' => 'Контакт'];

        $this->smarty->showPage('contact.tpl', $vars);

    }

    ///////////////////////////////////////////////////////////////////////////
    protected function saveContactMessage() {
        $isRequestAjax = Router::isRequest('AJAX');
        $isRequestPost = Router::isRequest('POST');

        // if the request is not POST and AJAX, don't do anything
        if ( ! ($isRequestAjax && $isRequestPost)) {
            return;
        }

        try {
            $entityManager = $this->getEntityManager();
            
            $message = $this->ContactMessage->prepareMessageFromPostRequest();
            $entityManager->persist($message);
            $entityManager->flush();

            $response = [
                'status'      => true,
                'type'        => 'contact_message',
                'success_msg' => $this->smarty->loadTemplate('elements/form-success-message.tpl', ['type' => 'contact']),
            ];
        }
        // if contact message save fails due to validation,
        // return the errors; otherwise log them
        catch (Exception $e) {
            $response = ['status' => false];

            if ($e instanceof ValidationException ) {
                $response['errors'] = $e->getErrors();
            }
            else {
                Logger::logError($e);
            }
        }

        $this->smarty->renderJSONContent($response);
    }
}