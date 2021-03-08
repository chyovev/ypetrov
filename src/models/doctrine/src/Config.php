<?php
class Config {
    private $id;

    private $setting;

    private $value;

    private $modifiedAt;

    ///////////////////////////////////////////////////////////////////////////
    public function getId(): int {
        return $this->id;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getSetting(): string {
        return $this->setting;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getValue(): ?string {
        return $this->value;
    }

}