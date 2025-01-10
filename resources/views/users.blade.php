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

<style>
.bg-lighter {
    background-color: #f6f6f6; /* Elige un color más claro */
}
</style>
<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    @extends('layouts.navbar')
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('layouts.head')
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="py-1 px-3 text-end" data-bs-toggle="modal" data-bs-target="#createCategoryModal"  onclick="getSucursales()">
        <label>
          + Crear Usuario
        </label>
      </div>
      <!-- Modal para crear usuario -->
      <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="createCategoryModalLabel">Crear Usuario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Formulario para crear el Usuario -->
              <form id="createUserForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="userName" class="form-label">Nombre</label>
            <input type="text" class="form-control border border-1 p-2" id="userName" name="name" placeholder="Ingrese el nombre del usuario" required>
          </div>
          <div class="mb-3">
            <label for="userEmail" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control border border-1 p-2" id="userEmail" name="email" placeholder="Ingrese el correo electrónico" required>
          </div>
          <div class="mb-3">
            <label for="userPassword" class="form-label">Contraseña</label>
            <input type="password" class="form-control border border-1 p-2" id="userPassword" name="password" placeholder="Ingrese la contraseña" required>
          </div>
          <div class="mb-3">
            <label for="roleSelector" class="form-label">Rol</label>
            <select id="roleSelector" name="role_id" class="form-control border border-1 p-2" required>
              <option value="">Seleccione un rol</option>
              <!-- Aquí se cargarán los roles dinámicamente -->
              @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
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
      <!-- Modal para crear usuario -->
      <div class="row">
        <div class="col-lg-8">
          <div class="row">
          @foreach($users as $user)
            <div class="col-md-4 col-4">
                <div class="card">
                    <!-- Ícono -->
                    <div class="card-header mx-4 p-3 text-center">
                        <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">category</i>
                        </div>
                    </div>
                    <!-- Información del usuario -->
                    <div class="card-body pt-0 p-3 text-center">
                        <!-- Nombre del usuario -->
                        <h6 class="text-center mb-0 opacity-9">{{ $user['name'] }}</h6>
                        <!-- <span class="text-xs">{{ $user['description'] }}</span> -->
                        
                        <!-- Inventario -->
                        <div class="mt-4">
                            <!-- <h6 class="text-left opacity-8">Inventario</h6> -->
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border rounded bg-lighter">
                                <div class="text-start column">
                                    <!-- <span class="d-block"><strong>{{ $user['inventory_code'] }}</strong> - {{ $user['location'] }}</span> -->
                                    <div class="text-xs text-bold mb-2">Descripcion:</div>
                                    <div class="text-xs text-bold mb-2">Identificacion:</div>
                                    <div class="text-xs text-bold">Categoria:</div>
                                </div>
                                <div class="text-end column">
                                    <div class="text-xs mb-2">{{ $user['description'] }}</div>
                                    <div class="text-xs mb-2">{{ $user['user_type'] }} - {{ $user['user_dni'] }}</div>
                                    <!-- <div class="text-xs">{{ $user['category_id'] }}</div> -->
                                    <div class="text-xs">{{ $user->category->name ?? 'Sin usuario' }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal para editar usuario -->
                        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <form id="editUserForm" enctype="multipart/form-data">
                                  @csrf
                                  <input type="hidden" id="editUserId" name="id">
                                  <div class="mb-3">
                                    <label for="editUserName" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="editUserName" name="name" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="editCategorySelector" class="form-label">Usuario</label>
                                    <select id="editCategorySelector" name="category_id" class="form-control" required>
                                      <option value="">Selecciona una usuario</option>
                                      <!-- Se llenará con las usuarios disponibles -->
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label for="editUserDni" class="form-label">Identificación</label>
                                    <input type="text" class="form-control" id="editUserDni" name="user_dni" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="editUserDescription" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="editUserDescription" name="description" rows="3" required></textarea>
                                  </div>
                                  <div class="d-flex flex-row-reverse">
                                    <button type="submit" class="btn btn-info">Guardar</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Acciones -->
                        <div class="mt-3">
                            <!-- <button class="btn btn-sm btn-outline-info">Editar</button> -->
                            <button class="btn btn-sm btn-outline-info btn-edit-user" data-bs-toggle="modal" data-bs-target="#editUserModal" data-user-id="{{ $user->id }}" onclick="getSucursales()">Editar</button>
                            <button class="btn btn-sm btn-outline-danger">Inactivar</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="row">
      <div class="col-md-12 mt-4">
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
document.getElementById('createUserForm').addEventListener('submit', function(event) {
  event.preventDefault(); // Evita el envío normal del formulario

  let formData = new FormData(this); // Crear un FormData con los datos del formulario

  fetch('api/create-user', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
    },
    body: formData
  })
  .then(response => {
        if (response.status === 201) { // Valida el código de estado HTTP
          alert('Usuario creado correctamente');
          window.location.reload();
        } else {
          throw new Error('Error al crear');
        }
      })
  .catch(error => console.error('Error:', error));
});

    function getSucursales() {
      fetch('api/categories', {
          method: 'post',
          headers: {
              'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          }
      })
      .then(response => response.json())
      .then(data => {
          const categorySelector = document.getElementById('categorySelector');
          
          // Limpiamos las opciones actuales
          categorySelector.innerHTML = '<option value="">Selecciona una usuario</option>';
          
          // Agregamos cada usuario al selector
          data.forEach(category => {
              const option = document.createElement('option');
              option.value = category.id;
              option.textContent = category.name;
              categorySelector.appendChild(option);
          });
          const editCategorySelector = document.getElementById('editCategorySelector');
          
          // Limpiamos las opciones actuales
          editCategorySelector.innerHTML = '<option value="">Selecciona una usuario</option>';
          
          // Agregamos cada usuario al selector
          data.forEach(category => {
              const option = document.createElement('option');
              option.value = category.id;
              option.textContent = category.name;
              editCategorySelector.appendChild(option);
          });
      })
      .catch(error => console.error('Error:', error));
  }
  </script>

</body>

</html>