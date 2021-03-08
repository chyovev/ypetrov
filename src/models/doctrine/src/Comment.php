<?php
class Comment {
    private $id;

    private $entityClass;

    private $entityId;

    private $username;

    private $body;

    private $ip;

    private $createdAt;

    private $entity;

    ///////////////////////////////////////////////////////////////////////////
    public function getDetails(): array {
        $comment = [
            'id'           => $this->getId(),
            'entity_class' => $this->getEntityClass(),
            'entity_id'    => $this->getEntityId(),
            'username'     => $this->getUsername(),
            'body'         => $this->getBody(),
            'ip'           => $this->getIp(),
            'created_at'   => $this->getCreatedAt(),
        ];

        return $comment;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getId(): int {
        return $this->id;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getEntityClass(): string {
        return $this->entityClass;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setEntityClass(string $entityClass): self {
        $this->entityClass = $entityClass;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getEntityId(): int {
        return $this->entityId;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setEntityId(int $entityId): self {
        $this->entityId = $entityId;

        return $this;
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
    public function setCreatedAt(): void {
        $this->createdAt = new DateTime();
    }

}