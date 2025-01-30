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
            <div class="row d-flex flex-wrap">
              @foreach($currencies as $currency)
                <div class="col-6">
                  <div class="card card-body border card-plain border-radius-lg d-flex justify-content-between align-items-center flex-row py-0">
                    <h6 class="mb-0">{{ $currency->name }} / {{$currency->code}}</h6>
                    <button class="btn btn-sm toggle-status-currency-btn pt-4 {{ $currency->status ? 'text-danger' : 'text-success'}}" 
                        data-id="{{ $currency->id }}" 
                        data-status="{{ $currency->status ? 'active' : 'inactive' }}">
                          {{ $currency->status ? 'Inactivar' : 'Activar' }}
                    </button>
                    <i class="material-symbols-rounded ms-auto text-dark cursor-pointer btn-edit-currency" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editCurrency" 
                    data-method-id="{{ $currency->id }}"
                    data-name="{{ $currency->name }}"
                    data-code="{{ $currency->code }}"
                    title="Editar Moneda">edit</i>
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
              <h6 class="mb-2">{{ $currencyName }}</h6>
              <div class="row">
              @foreach($methods as $method)
                <div class="col-md-6 mb-md-0 mb-4">
                  <div class="card card-body border border-radius-lg d-flex justify-content-between align-items-center flex-row mb-4 p-0">
                    @php
                        $qrImages = isset($method->qr_image) && is_string($method->qr_image) ? json_decode($method->qr_image, true) : [];
                    @endphp
                    <img src="{{ count($qrImages) > 0 ? asset('storage/' . $qrImages[0]) : '' }}" 
                        alt="Imagen del producto" 
                        class="d-none" 
                        style="width: 20%; height: 20%; object-fit: cover; border-radius: inherit;">
                    <div class="d-flex gap-2 align-items-center px-3">
                      <h6 class="mb-0">{{ $method->name }}</h6>
                      <i class="material-symbols-rounded ms-auto text-dark cursor-pointer btn-edit-method" 
                        title="Editar Método"
                        data-bs-toggle="modal" 
                        data-bs-target="#editPaymentMethod" 
                        data-method-id="{{ $method->id }}"
                        data-qr="{{ count($qrImages) > 0 ? asset('storage/' . $qrImages[0]) : '' }}"
                        data-name="{{ $method->name }}"
                        data-admin_name="{{ $method->admin_name }}"
                        data-currency="{{ $method->currency_id }}"
                        data-bank="{{ $method->bank }}"
                        data-dni="{{ $method->dni }}"
                        data-description="{{ $method->description }}">edit</i>
                    </div>
                    <button class="btn btn-sm toggle-status-btn pt-4 {{ $method->status ? 'text-danger' : 'text-success'}}" 
                        data-id="{{ $method->id }}" 
                        data-status="{{ $method->status ? 'active' : 'inactive' }}">
                          {{ $method->status ? 'Inactivar' : 'Activar' }}
                    </button>
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
                  <input type="text" class="form-control border border-1 p-2" id="currencyName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="currencyCode" class="form-label">Código</label>
                  <input type="text" class="form-control border border-1 p-2" id="currencyCode" name="code" required>
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
                  <input type="number" step="0.01" class="form-control border border-1 p-2" id="dollarRate" name="rate" required>
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
                  <input type="text" class="form-control border border-1 p-2" id="paymentMethodName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="paymentMethodCurrency" class="form-label">Moneda</label>
                  <select class="form-select border border-1 p-2" id="paymentMethodCurrency" name="currency" required>
                    <option value="" disabled selected>Selecciona una moneda</option>
                    @foreach($currencies as $currency)
                      <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="paymentMethodBenefit" class="form-label">Beneficiario</label>
                  <input type="text" class="form-control border border-1 p-2" id="paymentMethodBenefit" name="admin_name">
                </div>
                <div class="mb-3">
                  <label for="paymentMethodDni" class="form-label">DNI</label>
                  <input type="text" class="form-control border border-1 p-2" id="paymentMethodDni" name="dni">
                </div>
                <div class="mb-3">
                  <label for="paymentMethodBank" class="form-label">Banco</label>
                  <input type="text" class="form-control border border-1 p-2" id="paymentMethodBank" name="bank">
                </div>
                <div class="mb-3 d-flex flex-column">
                  <label for="paymentMethodQr" class="form-label">QR</label>
                    <input type="file" class="form-control border border-1 p-2 " id="image" name="image" accept="image/*">
                </div>
                <div class="d-flex flex-row-reverse">
                  <button type="submit" class="btn btn-info">Guardar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal para editar Método de Pago -->
      <div class="modal fade" id="editPaymentMethod" tabindex="-1" aria-labelledby="editPaymentMethod" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editPaymentMethodModalLabel">Editar Método de Pago</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="editPaymentMethodForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editMethodId" name="id">
                <div class="mb-3">
                  <label for="editPaymentMethodName" class="form-label">Nombre</label>
                  <input type="text" class="form-control border border-1 p-2" id="editPaymentMethodName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="editPaymentMethodCurrency" class="form-label">Moneda</label>
                  <select class="form-select border border-1 p-2" id="editPaymentMethodCurrency" name="currency" required>
                    <option value="" disabled selected>Selecciona una moneda</option>
                    @foreach($currencies as $currency)
                      <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="editPaymentMethodBenefit" class="form-label">Beneficiario</label>
                  <input type="text" class="form-control border border-1 p-2" id="editPaymentMethodBenefit" name="admin_name">
                </div>
                <div class="mb-3">
                  <label for="editPaymentMethodDni" class="form-label">DNI</label>
                  <input type="text" class="form-control border border-1 p-2" id="editPaymentMethodDni" name="dni">
                </div>
                <div class="mb-3">
                  <label for="editPaymentMethodBank" class="form-label">Banco</label>
                  <input type="text" class="form-control border border-1 p-2" id="editPaymentMethodBank" name="bank">
                </div>
                <div class="mb-3 d-flex flex-column">
                  <label for="editPaymentMethodQr" class="form-label">QR</label>
                  <img id="editPaymentMethodQrImage" src="" alt="Imagen del producto" class="d-none d-flex justify-content-center" style="width: 20%; height: 20%; object-fit: cover; border-radius: inherit;">
                  <label for="img" class="form-label">Cambiar QR</label>
                    <input type="file" class="form-control border border-1 p-2 " id="image" name="image" accept="image/*">
                </div>
                <div class="d-flex flex-row-reverse">
                  <button type="submit" class="btn btn-info">Guardar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal para editar Moneda -->
      <div class="modal fade" id="editCurrency" tabindex="-1" aria-labelledby="editCurrency" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editCurrencyModalLabel">Editar Moneda</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="editCurrencyForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editCurrencyId" name="id">
                <div class="mb-3">
                  <label for="editCurrencyName" class="form-label">Nombre</label>
                  <input type="text" class="form-control border border-1 p-2" id="editCurrencyName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="editCurrencyCode" class="form-label">Código</label>
                  <input type="text" class="form-control border border-1 p-2" id="editCurrencyCode" name="code" required>
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
      // Evento para llenar el modal con los datos de la categoría seleccionada
      document.querySelectorAll('.btn-edit-method').forEach(button => {
        button.addEventListener('click', function () {
          const methodId = this.getAttribute('data-method-id');
          const methodName = this.getAttribute('data-name') || ''; // Valor por defecto si es null
          const methodAdmin = this.getAttribute('data-admin_name') || '';
          const methodCurrency = this.getAttribute('data-currency') || '';
          const methodBank = this.getAttribute('data-bank') || '';
          const methodDni = this.getAttribute('data-dni') || '';
          const methodQr = this.getAttribute('data-qr') || null;

          // Asigna valores al formulario del modal
          document.getElementById('editMethodId').value = methodId;
          document.getElementById('editPaymentMethodName').value = methodName;
          document.getElementById('editPaymentMethodDni').value = methodDni;
          document.getElementById('editPaymentMethodBenefit').value = methodAdmin;
          document.getElementById('editPaymentMethodBank').value = methodBank;

          // Selecciona la moneda si está disponible
          const currencySelect = document.getElementById('editPaymentMethodCurrency');
          if (methodCurrency) {
            currencySelect.value = methodCurrency;
          } else {
            currencySelect.selectedIndex = 0; // Selecciona el placeholder por defecto
          }

          // Muestra la imagen del QR si existe o el ícono de foto si no
          const qrImage = document.getElementById('editPaymentMethodQrImage');
          const qrIcon = document.getElementById('editPaymentMethodQrIcon');
          const qrDelete = document.getElementById('btnRemoveQrImage');

          if (methodQr) {
            qrImage.src = methodQr; // Actualiza la URL de la imagen
            qrImage.classList.remove('d-none');
            qrDelete.classList.remove('d-none');
            qrIcon.classList.add('d-none'); // Esconde el ícono
          } else {
            qrDelete.classList.add('d-none');
            qrImage.classList.add('d-none'); // Esconde la imagen
            qrIcon.classList.remove('d-none'); // Muestra el ícono
          }
        });
      });
      // Evento para llenar el modal con los datos de la categoría seleccionada
      document.querySelectorAll('.btn-edit-currency').forEach(button => {
        button.addEventListener('click', function () {
          const methodId = this.getAttribute('data-method-id');
          const methodName = this.getAttribute('data-name') || '';
          const methodBank = this.getAttribute('data-code') || '';
          document.getElementById('editCurrencyId').value = methodId;
          document.getElementById('editCurrencyName').value = methodName;
          document.getElementById('editCurrencyCode').value = methodBank;
        });
      });
      document.getElementById('editCurrencyForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('editCurrencyId').value;
        let formData = new FormData(this);
        console.log("formData",formData)
        fetch(`/api/currencies/${id}/update`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          },
          body: formData,
        })
          .then(data => {
            if (data.status === 200) {
              alert('moneda actualizada');
              window.location.reload();
            } else {
              alert('Hubo un problema al actualizar.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
          });
      });
      document.querySelectorAll('.toggle-status-currency-btn').forEach(button => {
      button.addEventListener('click', function () {
        console.log("hola")
        const categoryId = this.getAttribute('data-id');
        const currentStatus = this.getAttribute('data-status');
        // Alternar el estado
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        // Hacer la petición AJAX para cambiar el estado
        fetch(`api/currencies/${categoryId}/currencyToggleStatus`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
          },
          body: { is_active: newStatus === 'active' ? 1 : 0 } // Enviar el estado como JSON
        })
        .then(response => {
          if (response.status === 200) { // Valida el código de estado HTTP
            alert('Categoría actualizada correctamente');
            window.location.reload();
          } else {
            throw new Error('Error al actualizar la categoría');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Ocurrió un error al actualizar la Categoría');
        });
        })
      });
      document.querySelectorAll('.toggle-status-btn').forEach(button => {
      button.addEventListener('click', function () {
        console.log("hola")
        const categoryId = this.getAttribute('data-id');
        const currentStatus = this.getAttribute('data-status');
        // Alternar el estado
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        // Hacer la petición AJAX para cambiar el estado
        fetch(`api/payment-methods/${categoryId}/toggleStatus`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
          },
          body: { is_active: newStatus === 'active' ? 1 : 0 } // Enviar el estado como JSON
        })
        .then(response => {
          if (response.status === 200) { // Valida el código de estado HTTP
            alert('Categoría actualizada correctamente');
            window.location.reload();
          } else {
            throw new Error('Error al actualizar la categoría');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Ocurrió un error al actualizar la Categoría');
        });
        })
      });
      document.getElementById('btnRemoveQrImage').addEventListener('click', function () {
        const methodId = document.getElementById('editMethodId').value;

        if (confirm('¿Estás seguro de que deseas eliminar este QR?')) {
          fetch(`/api/payment-methods/remove-qr/${methodId}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              // Actualizar la interfaz
              document.getElementById('editPaymentMethodQrImage').classList.add('d-none');
              document.getElementById('editPaymentMethodQrImage').src = '';
              document.getElementById('editPaymentMethodQrIcon').classList.remove('d-none');
              document.getElementById('btnRemoveQrImage').classList.add('d-none');
            } else {
              alert('Hubo un problema al eliminar el QR.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
          });
        }
      });
      document.getElementById('editPaymentMethodForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('editMethodId').value;
        let formData = new FormData(this);
        console.log("formData",formData)
        fetch(`/api/payment-methods/${id}/edit`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          },
          body: formData,
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              window.location.reload();
              // Actualizar la interfaz con el nuevo QR
              document.getElementById('editPaymentMethodQrImage').src = data.qr_image;
              document.getElementById('editPaymentMethodQrImage').classList.remove('d-none');
              document.getElementById('editPaymentMethodQrIcon').classList.add('d-none');
              document.getElementById('btnRemoveQrImage').classList.remove('d-none');
            } else {
              alert('Hubo un problema al actualizar el QR.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
          });
      });


  </script>
</body>
</html>
