<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Orden de Venta</title>
</head>
<body>
    <h1>Detalles de la Orden Nro {{ $order->id }}</h1>
    <p><strong>Cliente:</strong> {{ $order->user->name }} | <strong>Teléfono:</strong> {{ $order->user->phone_number ?? 'No registrado' }}</p>
    <p><strong>Entrega:</strong> {{ $order->preference }} | <strong>Dirección:</strong> {{ $order->address }}</p>
    <p><strong>Fecha:</strong> {{ $order->date }} | <strong>Estado:</strong> {{ $order->status == 0 ? 'En Proceso' : ($order->status == 1 ? 'Aprobado' : 'Negado') }}</p>

    <h2>Productos en la Orden</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Talla</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $detalle)
            <tr>
                <td>{{ $detalle->variant->product->name ?? 'Sin nombre' }}</td>
                <td>{{ $detalle->quantity }}</td>
                <td>{{ $detalle->variant->size ?? '' }}</td>
                <td>${{ number_format($detalle->price, 2) }}</td>
                <td>${{ number_format($detalle->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total Orden:</strong> ${{ number_format($totalOrden, 2) }}</p>

    <h2>Pagos Registrados</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Moneda</th>
                <th>Método de Pago</th>
                <th>Monto</th>
                <th>Beneficiario</th>
                <th>Banco</th>
                <th>Referencia</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->payments as $payment)
            <tr>
                <td>{{ $payment->currency }}</td>
                <td>{{ $payment->payment->name }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->payment->admin_name }}</td>
                <td>{{ $payment->payment->bank }}</td>
                <td>{{ $payment->reference ?? 'N/A' }}</td>
                <td>{{ $payment->status == 0 ? 'En Proceso' : ($payment->status == 1 ? 'Pagado' : 'Cancelado') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total Pagado:</strong> ${{ number_format($totalPagado, 2) }}</p>
</body>
</html>
