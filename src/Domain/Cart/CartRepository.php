<?php

declare(strict_types=1);

namespace App\Domain\Cart;

interface CartRepository
{
    public function getCurrent(): Cart;
    public function save(Cart $cart): void;
}
