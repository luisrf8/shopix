@extends('layouts.app')

@section('content')
    <div class="container-fluid py-2">
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
                  <button type="submit" class="btn btn-dark">Guardar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal para crear categoría -->
      <!-- Tabla para mostrar categorías -->
      <div class="row">
        {{ $authUser?->name }}
        {{ $authUser?->tenant_id }}

        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                <h6 class="text-white text-capitalize ps-3">CATEGORÍAS</h6>
                <div class="py-1 px-3 text-end " data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                  <label class="text-white">
                    + Agregar Categoría
                  </label>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead class="text-center">
                    <tr>
                      <th>Nombre</th>
                      <th>Descripción</th>
                      <th>Estado</th>
                      <th>Productos Disponibles</th>
                      <th>Editar</th>
                      <th>Activar / Inactivar</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($categories as $category)
                      <tr>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm">{{ $category['name'] }}</h6>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-xs text-secondary mb-0">{{ $category['description'] }}</p>
                        </td>
                        <td class="align-middle text-center text-sm">
                          <span class="badge badge-sm  {{ $category->is_active ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">{{ $category->is_active ? 'Activo' : 'Inactivo' }}
                          </span>
                        </td>
                        <td>{{ $category->total_available_items ?? 0 }}</td>
                        <td class="align-middle">
                          <a href="javascript:;"
                          class="text-secondary font-weight-bold text-xs btn-edit-user d-flex align-items-center justify-content-center"
                          data-bs-toggle="modal" 
                          data-bs-target="#editCategoryModal" 
                          data-category-id="{{ $category->id }}"
                          data-name="{{ $category->name }}"
                          data-description="{{ $category->description }}">
                            <!-- <i class="material-symbols-rounded opacity-10">edit</i> -->
                            Editar
                          </a>
                        </td>
                        <td class="align-middle">
                          <a href="javascript:;"
                          class="text-secondary font-weight-bold text-xs toggle-status-btn" 
                          data-id="{{ $category->id }}" 
                          data-status="{{ $category->is_active ? 'active' : 'inactive' }}">
                            {{ $category->is_active ? 'Inactivar' : 'Activar' }}
                          </a>
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
    @endsection

@push('scripts')
  <script>
    const authUser = @json($authUser);
    const tenantId = Number(authUser.tenant_id);
    document.getElementById('createCategoryForm').addEventListener('submit', function(event) {
      event.preventDefault();

      let formData = new FormData(this);
      formData.append('tenant_id', tenantId); // 👈 Agregas el tenant_id
      fetch('api/create-category', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
      })
      .then(response => {
        if (response.status === 201) {
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
            body: JSON.stringify({
              is_active: newStatus === 'active' ? 1 : 0,
            })
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
@endpush