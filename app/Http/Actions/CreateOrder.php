<?php

namespace App\Http\Actions;

use App\Models\Order;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateOrder
{
    use AsAction;

    public function handle(array $data): Order
    {
        try {
            //code...
            return Order::create($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
