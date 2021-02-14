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
    
}