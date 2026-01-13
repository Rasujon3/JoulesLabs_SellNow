<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepository;

final class CartRepositoryPDO implements CartRepository
{
    public function getCurrent(): Cart
    {
        return new Cart(); // simplified for demo
    }

    public function save(Cart $cart): void
    {
        // persist logic here (intentionally minimal)
    }
}
