<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Órdenes de Venta</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte de Órdenes de Venta ({{ ucfirst($range) }})</h2>
    <p>Desde: {{ $startDate->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Total Ítems</th>
                <th>Total Pagado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ $order->total_items }}</td>
                    <td>{{ number_format($order->payments->sum('amount'), 2) }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
