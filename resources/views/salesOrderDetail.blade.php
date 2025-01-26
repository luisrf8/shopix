<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Orden de Venta
  </title>
  <!--     Fonts and icons     -->
  <!-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" /> -->
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/842bd4ebad.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" /> -->
  <!-- CSS Files -->
  <link href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet">

</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    @extends('layouts.navbar')
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('layouts.head')
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-md-12 mt-4">
        <div class="">
            <div class="pb-0 px-3">
              <!-- <h6 class="mb-0">Productos En Inventario</h6> -->
              <h1>Detalles de la Orden Nro {{ $order->id }}</h1>
            </div>
            <div class="pt-4">
              <div class="row">
              @foreach($order->details as $detalle)
                <div class="col-md-4 mb-4">
                    <div class="card p-4 d-flex flex-row">
                        <div class="d-flex flex-column mx-3">
                            <h6 class="mb-2 text-sm">{{ $detalle->productVariant->product->name ?? 'Sin nombre' }}</h6>
                            <span class="mb-2 text-xs">Cantidad: 
                                <span class="text-dark font-weight-bold ms-sm-2">{{ $detalle->quantity }}</span>
                            </span>
                            <span class="mb-2 text-xs">Talla: 
                                <span class="text-dark font-weight-bold ms-sm-2">{{ $detalle->productVariant->size ?? '' }}</span>
                            </span>
                            <span class="mb-2 text-xs">Precio de Venta: 
                                <span class="text-dark font-weight-bold ms-sm-2">{{ $detalle->price ?? '' }} $</span>
                            </span>
                        </div>
                    </div>
                </div>
              @endforeach
              </div>
          </div>
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
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                // Opcional: actualizar la interfaz de usuario o limpiar los campos
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al registrar la llegada.');
            });
        });
    });
</script>

</body>

</html>