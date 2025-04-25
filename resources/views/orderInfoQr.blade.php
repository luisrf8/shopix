<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>Orden de Venta</title>
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="g-sidenav-show bg-gray-100 p-4">
    <div class="container-fluid">
        <div class="d-block d-md-none text-center">
            <img src="../../assets/img/inf.png" class="navbar-brand-img" width="150" height="150" alt="main_logo">
        </div>
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="text-center">Orden Nro {{ $order->id }}</h1>
            <img src="../../assets/img/inf.png" class="navbar-brand-img d-none d-md-block" width="150" height="150" alt="main_logo">
        </div>
        
        <p><strong>Cliente:</strong> {{ $order->user->name }} | <strong>Teléfono:</strong> {{ $order->user->phone_number ?? 'No registrado' }}</p>
        <p><strong>Entrega:</strong> {{ $order->preference }} | <strong>Dirección:</strong> {{ $order->address }}</p>
        
        <div class="d-flex flex-wrap align-items-center gap-2">
            <strong>Entregado:</strong>
            <span class="btn btn-sm {{ $order->status == 0 ? 'btn-outline-warning' : ($order->status == 1 ? 'btn-outline-success' : 'btn-outline-danger') }}">
                {{ $order->status == 0 ? 'Pendiente' : ($order->status == 1 ? 'Entregado' : ($order->status == 2 ? 'Cancelado' : 'Devolución')) }}
            </span>
        </div>
        
        <div class="d-flex flex-wrap gap-2">
            <p><strong>Fecha:</strong> {{ $order->date }}</p>
            <div class="d-flex align-items-center gap-2">
                <strong>Estado:</strong>
                <span class="btn btn-sm {{ $order->status == 0 ? 'btn-outline-warning' : ($order->status == 1 ? 'btn-outline-success' : 'btn-outline-danger') }}">
                    {{ $order->status == 0 ? 'En Proceso' : ($order->status == 1 ? 'Aprobado' : 'Negado') }}
                </span>
            </div>
        </div>
        
        <!-- Tabla de Detalles de la Orden -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Productos en la Orden</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
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
                </div>
                <p><strong>Total Orden:</strong> ${{ number_format($totalOrden, 2) }}</p>
            </div>
        </div>
        
        <!-- Tabla de Pagos -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Pagos Registrados</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
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
                                <td>{{ $payment->payment->name}}</td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment->admin_name }}</td>
                                <td>{{ $payment->payment->bank }}</td>
                                <td>{{ $payment->reference ?? 'N/A' }}</td>
                                <td>
                                    <span class="btn btn-sm {{ $payment->status == 0 ? 'btn-outline-warning' : ($payment->status == 1 ? 'btn-outline-success' : 'btn-outline-danger') }}">
                                        {{ $payment->status == 0 ? 'En Proceso' : ($payment->status == 1 ? 'Pagado' : 'Cancelado') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p><strong>Total Pagado:</strong> ${{ number_format($totalPagado, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
