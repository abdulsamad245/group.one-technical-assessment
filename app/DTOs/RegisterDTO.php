<?php

namespace App\DTOs;

use App\Traits\DTOToArray;

class RegisterDTO
{
    use DTOToArray;

    private string $name;
    private string $email;
    private string $password;
    private string $brandName;
    private string $brandSlug;
    private ?string $brandId = null;
    private string $role = 'user';

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getBrandName(): string
    {
        return $this->brandName;
    }

    public function getBrandSlug(): string
    {
        return $this->brandSlug;
    }

    public function getBrandId(): ?string
    {
        return $this->brandId;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setBrandName(string $brandName): self
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function setBrandSlug(string $brandSlug): self
    {
        $this->brandSlug = $brandSlug;

        return $this;
    }

    public function setBrandId(string $brandId): self
    {
        $this->brandId = $brandId;

        return $this;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
