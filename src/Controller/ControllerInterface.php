<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Request;

interface ControllerInterface
{
    public function handle(Request $request): void;
}
