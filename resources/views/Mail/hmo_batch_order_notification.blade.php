<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Batch Order Notification</title>
</head>

<body>
    <h2> New Order Batch Notification</h2>

    <p>
        Dear {{ $hmo->name }}, <br> <br>
        A new order batch has been submitted by {{ $order->provider_name }}.
    </p>

    <h3> Order Details: </h3>
    <ul>
        <li>Batch ID: {{ $order->batch_id }}</li>
        <li>Batch Name: {{ $order->batch->name }}</li>
        <li>Encounter Date: {{ $order->encounter_date }}</li>
        <li>Total Amount: {{ number_format($order->total, 2) }}</li>
    </ul>
    <br>
    <p>
        Thank you
    </p>
</body>

</html>
