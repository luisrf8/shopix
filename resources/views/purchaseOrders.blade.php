<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Productos
  </title>
  <!--     Fonts and icons     -->
  <!-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" /> -->
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <!-- <script src="https://kit.fontawesome.com/842bd4ebad.js" crossorigin="anonymous"></script> -->
  <!-- Material Icons -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" /> -->
  <!-- CSS Files -->
  <link href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet">
</head>

<body class="g-sidenav-show  bg-gray-100" id="d-body">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 d-none d-lg-block bg-white my-2" id="sidenav-main">
    @include('layouts.navbar')
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('layouts.head')
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="row mt-4">
        <div class="col-12">
            <div class="card">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                  <h6 class="text-white text-capitalize ps-3">ORDENES DE COMPRA REALIZADAS</h6>
                  <div class="py-1 px-3 text-end" data-bs-toggle="modal" data-bs-target="#reportModal">
                    <label class="text-white">
                      + Generar Reporte
                    </label>
                    <a class="text-white ms-6" href="/purchase">
                      + Generar Compra
                    </a>
                  </div>
                </div>
              </div> 
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-items-center mb-0">
                    <thead class="text-center">
                      <tr>
                        <th># Orden</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th># Productos</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($purchaseOrders as $order)
                        <tr>
                          <td>{{ $order->id }}</td>
                          <td>{{ $order->date }}</td>
                          <td>{{ $order->provider_id }}</td>
                          <td>{{ $order->total_items }}</td>
                          <td>
                            <a href="/order/{{ $order->id }}" class="text-secondary font-weight-bold text-xs toggle-status-btn">Ver Detalles</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
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
<!-- <script async defer src="https://buttons.github.io/buttons.js"></script> -->

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script>
    document.getElementById('createProductForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Evita el envío normal del formulario

      let formData = new FormData(this); // Crear un FormData con los datos del formulario
      const token = localStorage.getItem('authToken');
      fetch('api/create-product', {
        method: 'POST',
        headers: {
          // 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          'Authorization': `Bearer ${token}`
          
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.message === 'Product created successfully') {
          alert('Producto creado correctamente');
          // Cierra el modal y refresca o actualiza el contenido
          $('#createProductModal').modal('hide');
          // Aquí puedes añadir lógica para actualizar la lista de productos si existe
        } else {
          alert('Ocurrió un error al crear el producto');
        }
      })
      .catch(error => console.error('Error:', error));
    });

    document.getElementById('createCategoryForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Evita el envío normal del formulario

      let formData = new FormData(this); // Crear un FormData con los datos del formulario
      const token = localStorage.getItem('authToken');
      fetch('api/create-category', {
        method: 'POST',
        headers: {
          // 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          'Authorization': `Bearer ${token}`
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 201) {
          alert('Categoría creado correctamente');
          // Cierra el modal y refresca o actualiza el contenido
          $('#createCategoryModal').modal('hide');
          // Aquí puedes añadir lógica para actualizar la lista de Categoría si existe
        } else {
          alert('Ocurrió un error al crear la Categoría');
        }
      })
      .catch(error => console.error('Error:', error));
    });
    function getSucursales() {
      const token = localStorage.getItem('authToken');
      fetch('api/categories', {
          method: 'post',
          headers: {
              // 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
              'Authorization': `Bearer ${token}`
          }
      })
      .then(response => response.json())
      .then(data => {
          const categorySelector = document.getElementById('categorySelector');
          
          // Limpiamos las opciones actuales
          categorySelector.innerHTML = '<option value="">Selecciona una categoría</option>';
          
          // Agregamos cada categoría al selector
          data.forEach(category => {
              const option = document.createElement('option');
              option.value = category.id;
              option.textContent = category.name;
              categorySelector.appendChild(option);
          });
      })
      .catch(error => console.error('Error:', error));
  }
  </script>

</body>

</html>