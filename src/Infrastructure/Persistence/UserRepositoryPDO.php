<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use PDO;
use App\Domain\User\User;
use App\Domain\User\UserRepository;

final class UserRepositoryPDO implements UserRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare(
            'SELECT id, email, password FROM users WHERE email = :email LIMIT 1'
        );

        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row
            ? new User(
                (int) $row['id'],
                $row['email'],
                $row['password']
            )
            : null;
    }
}
