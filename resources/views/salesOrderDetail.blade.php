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

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    @extends('layouts.navbar')
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('layouts.head')
    <!-- End Navbar -->
    <div class="container-fluid">
      <h1>Detalles de la Orden Nro {{ $order->id }}</h1>
      <input type="text" id="user-name" class="d-none" value="{{ $order->user->name }}" readonly>
      <input type="text" id="user-email" class="d-none" value="{{ $order->user->email }}" readonly>
      <input type="text" id="user-phone" class="d-none" value="{{ $order->user->phone_number ?? 'No registrado' }}" readonly>
      <p><strong>Cliente:</strong> {{ $order->user->name }} | <strong>Teléfono:</strong> {{ $order->user->phone_number ?? 'No registrado' }}</p>
      <p><strong>Entrega:</strong> {{ $order->preference }} | <strong>Dirección:</strong> {{ $order->address }}</p>
      <div class="d-flex aling-items-center gap-2">
            <strong>Entregado:</strong>
            <select id="deliver-status" class="btn btn-sm toggle-status-btn 
              {{ $order->deliver_status == 0 ? 'btn-outline-warning' : ($order->deliver_status == 1 ? 'btn-outline-success' : 'btn-outline-danger') }} 
              " onchange="updateDeliverStatus({{ $order->id }})">
              <option value="0" {{ $order->deliver_status == 0 ? 'selected' : '' }}>Pendiente ↓</option>
              <option value="1" {{ $order->deliver_status == 1 ? 'selected' : '' }}>Entregado ↓</option>
              <option value="2" {{ $order->deliver_status == 2 ? 'selected' : '' }}>Cancelado ↓</option>
              <!-- <option value="3" {{ $order->deliver_status == 3 ? 'selected' : '' }}>Devolucion ↓</option> -->
            </select>
          </div>
      <div class="d-flex gap-2">
        <div>
          <p><strong>Fecha:</strong> {{ $order->date }} |
        </div> 
        <div>
          <div class="d-flex aling-items-center gap-2">
            <strong>Estado:</strong>
            <select id="order-status" class="btn btn-sm toggle-status-btn 
              {{ $order->status == 0 ? 'btn-outline-warning' : ($order->status == 1 ? 'btn-outline-success' : 'btn-outline-danger') }} 
              " onchange="updateOrderStatus({{ $order->id }})">
              <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>En Proceso ↓</option>
              <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Aprobado ↓</option>
              <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Negado ↓</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Tabla de Detalles de la Orden -->
      <div class="card mt-4">
        <div class="card-header">
          <h6 class="mb-0">Productos en la Orden</h6>
        </div>
        <div class="card-body">
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
          <p><strong>Total Orden:</strong> ${{ number_format($totalOrden, 2) }}</p>
        </div>
      </div>

      <!-- Tabla de Pagos -->
      <div class="card mt-4">
        <div class="card-header">
          <h6 class="mb-0">Pagos Registrados</h6>
        </div>
        <div class="card-body">
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
                  <select class="btn btn-sm toggle-status-btn 
              {{ $payment->status == 0 ? 'btn-outline-warning' : ($payment->status == 1 ? 'btn-outline-success' : 'btn-outline-danger') }} 
              " onchange="updatePaymentStatus({{ $payment->id }})">
                    <option value="0" {{ $payment->status == 0 ? 'selected' : '' }}>En Proceso ↓</option>
                    <option value="1" {{ $payment->status == 1 ? 'selected' : '' }}>Pagado ↓</option>
                    <option value="3" {{ $payment->status == 3 ? 'selected' : '' }}>Cancelado ↓</option>
                  </select>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <p><strong>Total Pagado:</strong> ${{ number_format($totalPagado, 2) }}</p>
        </div>
      </div>
    </div>
  </main>
  
<!-- Core JS Files -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script>
function updateOrderStatus(orderId) {
    const status = document.getElementById('order-status').value;
    const userName = document.getElementById('user-name').value;
    const userPhone = document.getElementById('user-phone').value;

    fetch(`/api/order/${orderId}/status/update`, {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);

            // Descargar el PDF automáticamente
            const link = document.createElement("a");
            link.href = data.pdf_url;
            link.download = `orden-${orderId}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            location.reload();
        }
    })
    .catch(error => console.error("Error:", error));
}

function updateDeliverStatus(paymentId) {
      const status = event.target.value;
      const userName = document.getElementById('user-name').value;  // Si usas un input
      const userPhone = document.getElementById('user-phone').value;  // Si usas un input

      fetch(`/api/deliver/${paymentId}/status/update`, {
        method: "POST",
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
        },
        body:JSON.stringify({ status: status })
      })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        location.reload();
      })
      .catch(error => console.error("Error:", error));
    }
    function updatePaymentStatus(paymentId) {
      const status = event.target.value;
      const userName = document.getElementById('user-name').value;  // Si usas un input
      const userPhone = document.getElementById('user-phone').value;  // Si usas un input
      const userEmail = document.getElementById('user-email').value;  // Si usas un input
      const message = encodeURIComponent(`Hola ${userName} ¡Tu pago ha sido confirmado y aprobado!`);

      fetch(`/api/payment/${paymentId}/status/update`, {
        method: "POST",
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
        },
        body:JSON.stringify({ status: status, email: userEmail })
      })
      .then(response => response.json())
      .then(data => {
        console.log("data", data)
        alert(data.message);
        location.reload();
      })
      .catch(error => console.error("Error:", error));
    }
</script>

</body>

</html>