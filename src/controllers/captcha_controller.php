<?php
class CaptchaController extends AppController {

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $code = $this->generateCaptcha();

        $json = ['image' => $code->getImage()];

        $this->smarty->renderJSONContent($json);
    }

}
