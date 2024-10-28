<?php

namespace App\Http\Actions;

use App\Models\Batch;
use App\Models\Hmo;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class BatchOrder
{
    use AsAction;

    public function handle(Order $order): Batch
    {
        $hmo = $order?->hmo;
        $batch_date = ($hmo->is_batched_by_encounter)
            ? Carbon::parse($order->encounter_date)
            : Carbon::now();
        $batch_name = $order->provider_name . " " . $batch_date->format('F Y');

        try {
            //code...
            $batch = Batch::updateOrCreate(['name' => $batch_name],[
                'provider_name' => $order->provider_name,
                'hmo_code' => $order->hmo_code,
                'batch_date' => $batch_date?->toDateString()
            ]);
            $batch->orders()->save($order);
            return $batch;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
