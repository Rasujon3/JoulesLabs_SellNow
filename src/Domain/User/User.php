<?php

declare(strict_types=1);

namespace App\Domain\User;

final class User
{
    public function __construct(
        public int $id,
        public string $email,
        public string $passwordHash
    ) {
    }
}
