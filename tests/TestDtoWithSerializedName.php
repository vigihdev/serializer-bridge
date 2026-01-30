<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Tests;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TestDtoWithSerializedName
{
    #[SerializedName('first_name')]
    public readonly string $firstName;

    #[SerializedName('last_name')]
    public readonly string $lastName;

    #[SerializedName('user_email')]
    public readonly ?string $userEmail;

    #[SerializedName('user_age')]
    public readonly int $userAge;

    public function __construct(
        string $firstName,
        string $lastName,
        ?string $userEmail,
        int $userAge
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->userEmail = $userEmail;
        $this->userAge = $userAge;
    }
}