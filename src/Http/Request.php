<?php

declare(strict_types=1);

namespace App\Http;

final class Request
{
    public function post(string $key): ?string
    {
        return isset($_POST[$key])
            ? trim(htmlspecialchars($_POST[$key], ENT_QUOTES))
            : null;
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
}
