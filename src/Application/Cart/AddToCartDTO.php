<?php

declare(strict_types=1);

namespace App\Application\Cart;

final class AddToCartDTO
{
    public function __construct(
        public int $productId,
        public int $quantity
    ) {
        if ($this->productId <= 0) {
            throw new \InvalidArgumentException('Invalid product ID');
        }

        if ($this->quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be positive');
        }
    }
}
