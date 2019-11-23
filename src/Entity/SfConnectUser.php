<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SymfonyCorp\Connect\Api\Entity\User;

/**
 * @ORM\Embeddable()
 */
class SfConnectUser
{
    /**
     * @ORM\Column(type="string")
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    public static function buildFromApiUser(User $user): self
    {
        $self = new self();
        $self->uuid = $user->get('uuid');
        $self->username = $user->get('username');
        $self->name = $user->get('name');
        $self->email = $user->get('email');

        return $self;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
