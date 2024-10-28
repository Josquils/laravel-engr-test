<?php

namespace App\Http\Controllers;

use App\Http\Actions\BatchOrder;
use App\Http\Actions\CreateOrder;
use App\Http\Requests\OrderRequest;
use App\Mail\OrderBatchNotification;
use App\Models\Hmo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $hmos = Hmo::all();
        return Inertia::render('SubmitOrder', ['hmos' => $hmos]);
    }

    public function save(OrderRequest $request, CreateOrder $createOrder, BatchOrder $batchOrder)
    {
        $order_data = $request->orderData();
        $order = $createOrder->handle($order_data);

        $batchOrder->handle($order);

        Mail::to(config('app.email'))->send(new OrderBatchNotification($order));

        return back();
    }
}
