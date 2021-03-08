<?php
class ContactMessage {
    private $id;

    private $username;

    private $email;

    private $body;

    private $ip;

    private $createdAt;

    private $entity;

    ///////////////////////////////////////////////////////////////////////////
    public function getId(): int {
        return $this->id;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getUsername(): string {
        return $this->username;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setUsername(string $username): self {
        $this->username = $username;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getEmail(): ?string {
        return $this->email;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setEmail(?string $email = NULL): self {
        $this->email = $email;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getBody(): string {
        return $this->body;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setBody(string $body): self {
        $this->body = $body;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getIp(): ?int {
        return $this->ip;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getActualIp(): ?string {
        return isset($this->ip) ? long2ip($this->ip) : NULL;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setIp(?string $ip = NULL): self {
        $intIp = isset($ip) ? ip2long($ip) : NULL;
        $this->ip = $intIp;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setCreatedAt(?DateTime $date = NULL): self {
        if ( ! $date) {
            $date = new DateTime();
        }

        $this->createdAt = $date;

        return $this;
    }

}