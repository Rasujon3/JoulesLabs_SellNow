<?php
declare(strict_types=1);

namespace App\Application\Cart;

use App\Domain\Cart\CartRepository;

final class AddToCartService
{
    public function __construct(
        private CartRepository $repository
    ) {
    }

    public function execute(AddToCartDTO $dto): void
    {
        $cart = $this->repository->getCurrent();
        $cart->addItem($dto->productId, $dto->quantity);
        $this->repository->save($cart);
    }
}
