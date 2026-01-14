<?php

declare(strict_types=1);

namespace App\Support;

final class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function verify(?string $token): void
    {
        if (
            !$token ||
            !isset($_SESSION[self::SESSION_KEY]) ||
            !hash_equals($_SESSION[self::SESSION_KEY], $token)
        ) {
            http_response_code(419);
            die('Invalid CSRF token');
        }
    }
}
