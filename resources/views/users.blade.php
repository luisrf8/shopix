<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Usuarios
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
        <div class="col-12">
          <div class="row">
          @foreach($users as $user)
            <div class="col-md-3 col-3">
                <div class="card mb-4">
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
                        
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border rounded bg-lighter">
                                <div class="text-start column">
                                    <div class="text-xs text-bold">Rol:</div>
                                </div>
                                <div class="text-end column">
                                    <div class="text-xs ">{{ $user['role']['name'] }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- Acciones -->
                        <div class="mt-3">
                            <button 
                              class="btn btn-sm btn-outline-info btn-edit-user" 
                              data-bs-toggle="modal" 
                              data-bs-target="#editUserModal" 
                              data-user-id="{{ $user['id'] }}"
                              data-name="{{ $user['name'] }}"
                              data-email="{{ $user['email'] }}"
                              data-role="{{ $user['role']['id'] }}">
                              Editar
                            </button>
                            <button class="btn btn-sm toggle-status-btn {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success'}}" 
                              data-id="{{ $user->id }}" 
                              data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
                              {{ $user->is_active ? 'Inactivar' : 'Activar' }}
                            </button>
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
                                <form id="editUserForm" class="text-start" enctype="multipart/form-data">
                                  @csrf
                                  <input type="hidden" id="editUserId" name="id">
                                  <div class="mb-3">
                                    <label for="editUserName" class="form-label">Nombre</label>
                                    <input type="text" class="form-control border border-1 p-2" id="editUserName" name="name" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="editUserEmail" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control border border-1 p-2" id="editUserEmail" name="email" placeholder="Ingrese el correo electrónico" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="editUserRoleSelector" class="form-label">Rol</label>
                                    <select id="editUserRoleSelector" name="role_id" class="form-control border border-1 p-2" required>
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

                        
                    </div>
                </div>
            </div>
            @endforeach
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
    document.querySelectorAll('.btn-edit-user').forEach(button => {
      button.addEventListener('click', function () {
        const userId = this.getAttribute('data-user-id');
        const userName = this.getAttribute('data-name');
        const userEmail = this.getAttribute('data-email');
        const userRoleId = this.getAttribute('data-role');

        document.getElementById('editUserId').value = userId;
        document.getElementById('editUserName').value = userName;
        document.getElementById('editUserEmail').value = userEmail;
        document.getElementById('editUserRoleSelector').value = userRoleId;
      });
    });
        // Enviar la actualización al servidor
    document.getElementById('editUserForm').addEventListener('submit', function (event) {
      event.preventDefault(); // Evita el envío normal del formulario

      const formData = new FormData(this);
      const userId = formData.get('id');

      fetch(`api/user/${userId}`, {
        method: 'POST', // Usa 'PUT' si tu API lo requiere
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
      })
      .then(response => {
        if (response.status === 200) { // Valida el código de estado HTTP
          alert('Usuario actualizado correctamente');
          window.location.reload();
        } else {
          throw new Error('Error al actualizar Usuario');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al actualizar Usuario');
      });
    });
    document.querySelectorAll('.toggle-status-btn').forEach(button => {
      button.addEventListener('click', function () {
        console.log("hola")
        const userId = this.getAttribute('data-id');
        const currentStatus = this.getAttribute('data-status');
        // Alternar el estado
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        // Hacer la petición AJAX para cambiar el estado
        fetch(`api/users/${userId}/toggle-status`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
          },
          body: { is_active: newStatus === 'active' ? 1 : 0 } // Enviar el estado como JSON
        })
        .then(response => {
          if (response.status === 200) { // Valida el código de estado HTTP
            alert('Usuario actualizado correctamente');
            window.location.reload();
          } else {
            throw new Error('Error al actualizar Usuario');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Ocurrió un error al actualizar Usuario');
        });
        })
      });
  </script>

</body>

</html>