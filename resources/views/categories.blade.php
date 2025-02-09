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
  <script src="https://kit.fontawesome.com/842bd4ebad.js" crossorigin="anonymous"></script>
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
      <div class="py-1 px-3 text-end" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <label>
          + Agregar Categoría
        </label>
        <!-- <label>
          Editar Categoría
        </label> -->
      </div>
      <!-- Modal para crear categoría -->
      <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="createCategoryModalLabel">Crear Categoría</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Formulario para crear el Categoría -->
              <form id="createCategoryForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label for="categoryName" class="form-label">Nombre</label>
                  <input type="text" class="form-control border border-1 p-2" id="categoryName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="categoryDescription" class="form-label">Descripción</label>
                  <textarea class="form-control border border-1 p-2" id="categoryDescription" name="description" rows="3" required></textarea>
                </div>
                <div class="d-flex flex-row-reverse">
                  <button type="submit" class="btn btn-info">Guardar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal para crear categoría -->
      <div class="row">
        <div class="col-lg-8">
          <div class="row">
            @foreach($categories as $category)
              <div class="col-md-4 col-4">
                <a class="text-decoration-none">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                        <!-- Puedes cambiar el ícono dinámicamente si tienes un campo para eso -->
                        <i class="material-symbols-rounded opacity-10">category</i>
                      </div>
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0 opacity-9">{{ $category['name'] }}</h6>
                      <span class="text-xs">{{ $category['description'] }}</span>
                    </div>
                    <div class="d-flex w-100 justify-content-between align-items-center px-4">
                      <button class="btn btn-sm toggle-status-btn {{ $category->is_active ? 'btn-outline-danger' : 'btn-outline-success'}}" 
                              data-id="{{ $category->id }}" 
                              data-status="{{ $category->is_active ? 'active' : 'inactive' }}">
                          {{ $category->is_active ? 'Inactivar' : 'Activar' }}
                      </button>
                      <button 
                        class="btn btn-sm btn-outline-info btn-edit-user" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editCategoryModal" 
                        data-category-id="{{ $category->id }}"
                        data-name="{{ $category->name }}"
                        data-description="{{ $category->description }}">
                        Editar
                      </button>
                      <!-- <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-name="{{ $category->name }}" data-descrption="{{ $category->description }}" data-category-id="{{ $category->id }}">Editar</button> -->
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <!-- Modal para editar categoría -->
      <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="editCategoryForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editCategoryId" name="id">
                <div class="mb-3">
                  <label for="editCategoryName" class="form-label">Nombre</label>
                  <input type="text" class="form-control border border-1 p-2" id="editCategoryName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="editCategoryDescription" class="form-label">Descripción</label>
                  <textarea class="form-control border border-1 p-2" id="editCategoryDescription" name="description" rows="3" required></textarea>
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

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script>
    document.getElementById('createCategoryForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Evita el envío normal del formulario

      let formData = new FormData(this); // Crear un FormData con los datos del formulario
      fetch('api/create-category', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
      })
      .then(response => {
        if (response.status === 201) { // Valida el código de estado HTTP
          alert('Categoría creada correctamente');
          window.location.reload();
        } else {
          throw new Error('Error al crear la categoría');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al crear la Categoría');
      });
    });
    // Evento para llenar el modal con los datos de la categoría seleccionada
    document.querySelectorAll('.btn-edit-user').forEach(button => {
      button.addEventListener('click', function () {
        const categoryId = this.getAttribute('data-category-id');
        const categoryName = this.getAttribute('data-name');
        const categoryDescription = this.getAttribute('data-description');

        document.getElementById('editCategoryId').value = categoryId;
        document.getElementById('editCategoryName').value = categoryName;
        document.getElementById('editCategoryDescription').value = categoryDescription;
      });
    });

    // Enviar la actualización al servidor
    document.getElementById('editCategoryForm').addEventListener('submit', function (event) {
      event.preventDefault(); // Evita el envío normal del formulario

      const formData = new FormData(this);
      const categoryId = formData.get('id');

      fetch(`api/categories/${categoryId}`, {
        method: 'POST', // Usa 'PUT' si tu API lo requiere
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
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
    });
    document.querySelectorAll('.toggle-status-btn').forEach(button => {
      button.addEventListener('click', function () {
        console.log("hola")
        const categoryId = this.getAttribute('data-id');
        const currentStatus = this.getAttribute('data-status');
        // Alternar el estado
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        // Hacer la petición AJAX para cambiar el estado
        fetch(`api/categories/${categoryId}/toggle-status`, {
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

  </script>

</body>

</html>