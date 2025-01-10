<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>El Hombre Casual</title>
  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100" id="d-body">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 d-none d-lg-block bg-white my-2" id="sidenav-main">
    @include('layouts.navbar')
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    @include('layouts.head')
    <!-- End Navbar -->

    <div class="container-fluid py-2">
      <div class="row align-items-center">
        <div class="col-6 d-flex align-items-center">
          <h6 class="mb-0">Tasa actual: <span id="currentDollarRate">{{$dollarRate ? number_format($dollarRate->rate, 2) : 'N/A'}}</span> USD</h6>
        </div>
        <div class="col-6 text-end">
          <button class="btn bg-gradient-success mb-0" data-bs-toggle="modal" data-bs-target="#updateDollarRateModal">
            <i class="material-symbols-rounded text-sm">currency_exchange</i>&nbsp;&nbsp;Actualizar Tasa del Dólar
          </button>
        </div>
      </div>
      <!-- Monedas Section -->
      <div class="col-md-12 mb-lg-0 mb-4">
        <div class="card mt-4 mb-4">
          <div class="card-header pb-0 p-3">
            <div class="row">
              <div class="col-6 d-flex align-items-center">
                <h6 class="mb-0">Monedas</h6>
              </div>
              <div class="col-6 text-end">
                <button class="btn bg-gradient-info mb-0" data-bs-toggle="modal" data-bs-target="#createCurrencyModal">
                  <i class="material-symbols-rounded text-sm">add</i>&nbsp;&nbsp;Nueva Moneda
                </button>
              </div>
            </div>
          </div>
          <div class="card-body p-3">
            <!-- Lista de Monedas -->
            <div class="row">
              @foreach($currencies as $currency)
                <div class="col-md-6 mb-md-0 mb-4">
                  <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                    <h6 class="mb-0">{{ $currency->name }} / {{$currency->code}}</h6>
                    <i class="material-symbols-rounded ms-auto text-dark cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Moneda">edit</i>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <!-- Métodos de Pago Section -->
      <div class="col-md-12 mb-lg-0 mb-4">
        <div class="card mt-4">
          <div class="card-header pb-0 p-3">
            <div class="row">
              <div class="col-6 d-flex align-items-center">
                <h6 class="mb-0">Métodos de Pago</h6>
              </div>
              <div class="col-6 text-end">
                <button class="btn bg-gradient-info mb-0" data-bs-toggle="modal" data-bs-target="#createPaymentMethodModal">
                  <i class="material-symbols-rounded text-sm">add</i>&nbsp;&nbsp;Nuevo Método de Pago
                </button>
              </div>
            </div>
          </div>
          <div class="card-body p-3">
            @foreach($groupedPaymentMethods as $currencyName => $methods)
              <h6 class="mb-0">{{ $currencyName }}</h6>
              <div class="row">
                @foreach($methods as $method)
                  <div class="col-md-6 mb-md-0 mb-4">
                    <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                      <h6 class="mb-0">{{ $method->name }}</h6>
                      <i class="material-symbols-rounded ms-auto text-dark cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Método">edit</i>
                    </div>
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
      </div>


      <!-- Modal: Crear Moneda -->
      <div class="modal fade" id="createCurrencyModal" tabindex="-1" aria-labelledby="createCurrencyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="createCurrencyModalLabel">Crear Moneda</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="createCurrencyForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label for="currencyName" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="currencyName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="currencyCode" class="form-label">Código</label>
                  <input type="text" class="form-control" id="currencyCode" name="code" required>
                </div>
                <div class="d-flex flex-row-reverse">
                  <button type="submit" class="btn btn-info">Guardar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal: Actualizar Tasa del Precio del Dólar -->
      <div class="modal fade" id="updateDollarRateModal" tabindex="-1" aria-labelledby="updateDollarRateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="updateDollarRateModalLabel">Actualizar Tasa del Dólar</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="updateDollarRateForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label for="dollarRate" class="form-label">Tasa de Cambio</label>
                  <input type="number" step="0.01" class="form-control" id="dollarRate" name="rate" required>
                </div>
                <div class="d-flex flex-row-reverse">
                  <button type="submit" class="btn btn-info">Actualizar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal: Crear Método de Pago -->
      <div class="modal fade" id="createPaymentMethodModal" tabindex="-1" aria-labelledby="createPaymentMethodModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="createPaymentMethodModalLabel">Crear Método de Pago</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="createPaymentMethodForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label for="paymentMethodName" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="paymentMethodName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="paymentMethodCurrency" class="form-label">Moneda</label>
                  <select class="form-select p-2" id="paymentMethodCurrency" name="currency" required>
                    <option value="" disabled selected>Selecciona una moneda</option>
                    @foreach($currencies as $currency)
                      <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="d-flex flex-row-reverse">
                  <button type="submit" class="btn btn-info">Guardar</button>
                </div>
              </form>
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
  <script>

    document.getElementById('createCurrencyForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Evita el envío normal del formulario
      console.log("hola")
      let formData = new FormData(this); // Crear un FormData con los datos del formulario
      console.log("formData", formData)
      fetch('api/currencies/create', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
      })
      .then(response => {
        if (response.status === 201) { // Valida el código de estado HTTP
          // alert('Moneda creada correctamente');
          // window.location.reload();
        } else {
          throw new Error('Error al crear la Moneda');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al crear la Moneda');
      });
    });


    document.getElementById('createPaymentMethodForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Evita el envío normal del formulario

      let formData = new FormData(this); // Crear un FormData con los datos del formulario
      fetch('api/payment-methods/create', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
      })
      .then(response => {
        if (response.status === 201) { // Valida el código de estado HTTP
          alert('Método de pago creado correctamente');
          window.location.reload();
        } else {
          throw new Error('Error al crear Método de pago');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al Método de pago');
      });
    });
    // Actualizar la tasa del dólar
    document.getElementById('updateDollarRateForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Evita que el formulario se envíe de manera convencional

      let formData = new FormData(this); // Crear un FormData con los datos del formulario
      fetch('api/dollar-rate/update', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
      })
        .then(response => {
          if (response.status === 201) {
            alert('Tasa del dólar actualizada exitosamente');
            $('#updateDollarRateModal').modal('hide'); // Cierra el modal
            location.reload(); // Recarga la página para mostrar los cambios
          } else {
            alert('Hubo un error al actualizar la tasa del dólar');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Hubo un error inesperado. Intente nuevamente');
        })
      });
  </script>
</body>
</html>
