<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Tests;

class TestDto
{
    public string $name;
    public int $age;
    public ?string $email;

    // Getters and setters for Symfony serializer compatibility
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}