<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Request;
use App\Application\Cart\AddToCartDTO;
use App\Application\Cart\AddToCartService;

final class CartController implements ControllerInterface
{
    public function __construct(
        private AddToCartService $service
    ) {
    }

    public function handle(Request $request): void
    {
        $dto = new AddToCartDTO(
            (int) $request->post('product_id'),
            (int) $request->post('quantity')
        );

        $this->service->execute($dto);
    }
}
