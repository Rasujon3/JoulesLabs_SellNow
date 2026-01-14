<?php
declare(strict_types=1);

namespace App\Application\Auth;

use App\Domain\User\UserRepository;

final class LoginService
{
    public function __construct(
        private UserRepository $users
    ) {
    }

    public function authenticate(string $email, string $password): int
    {
        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($password, $user->passwordHash)) {
            throw new \RuntimeException('Invalid credentials');
        }

        // Password rehash if needed (future-proof)
        if (password_needs_rehash($user->passwordHash, PASSWORD_DEFAULT)) {
            // optional: persist new hash
        }

        return $user->id;
    }
}
