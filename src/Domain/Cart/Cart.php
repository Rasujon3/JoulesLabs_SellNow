<?php

declare(strict_types=1);

namespace App\Domain\Cart;

final class Cart
{
    private array $items = [];

    public function addItem(int $productId, int $quantity): void
    {
        $this->items[$productId] =
            ($this->items[$productId] ?? 0) + $quantity;
    }

    public function items(): array
    {
        return $this->items;
    }
}
