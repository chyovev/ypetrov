<?php

final class ErrorsController extends AppController {

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $vars = [
            'noindex' => true,
        ];

        $this->smarty->showPage('layout/error404.tpl', $vars);
    }

}