<?php
use Mobicms\Captcha\Captcha as MobiCmsCaptcha;

// exctending Mobicms Captcha functionality
class Captcha extends MobiCmsCaptcha {

    private $code;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(int $lenght = 5) {
        $this->lenghtMax = $lenght;
        $this->lenghtMin = $lenght;

        $this->code = parent::generateCode();

        // store captcha to session
        setCaptchaSession($this->code);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getCode(): string {
        return $this->code;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getImage(): string {
        return parent::generateImage($this->code);
    }
}
