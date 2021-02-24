<?php

trait ValidationTrait {

    ///////////////////////////////////////////////////////////////////////////
    private function checkStringAgainstMaxLength(string $string, int $length) {
        return (bool) (mb_strlen($string, 'utf-8') > $length);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function addError(string $message, string $field = 'general'): void {
        $this->errors[$field][] = $message;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getErrors(): array {
        return $this->errors;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function isCaptchaCorrect(): bool {
        $request = getRequestVariables('post', ['captcha']);
        $session = getCaptchaSession();

        return (mb_strtolower($request['captcha'], 'utf-8') === mb_strtolower($session, 'utf-8'));
    }
    
}