@extends('layouts.app')

@section('title', 'Tiendas')

@section('content')
<div class="container-fluid py-2">
  <!-- Tabla para mostrar tenants -->
  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3">TIENDAS</h6>
            <a href="/create-tenant" blank="_blank">
              <div class="py-1 px-3 text-end">
                <label class="text-white">+ Agregar Tienda</label>
              </div>
            </a>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead class="text-center">
                <tr>
                  <th>Nombre</th>
                  <th>Slug</th>
                  <th>Email</th>
                  <th>Logo</th>
                  <th>Editar</th>
                  <th>Eliminar</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($tenants as $tenant)
                  <tr>
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->slug }}</td>
                    <td>{{ $tenant->email }}</td>
                    <td>{{ $tenant->logo }}</td>
                    <td>
                      <a href="javascript:;" 
                         class="text-secondary font-weight-bold text-xs btn-edit-tenant"
                         data-bs-toggle="modal" 
                         data-bs-target="#editTenantModal" 
                         data-id="{{ $tenant->id }}"
                         data-name="{{ $tenant->name }}"
                         data-slug="{{ $tenant->slug }}"
                         data-email="{{ $tenant->email }}"
                         data-logo="{{ $tenant->logo }}">
                        Editar
                      </a>
                    </td>
                    <td>
                      <a href="javascript:;" 
                         class="text-danger font-weight-bold text-xs btn-delete-tenant"
                         data-id="{{ $tenant->id }}">
                        Eliminar
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

  <!-- Modal para editar tenant -->
  <div class="modal fade" id="editTenantModal" tabindex="-1" aria-labelledby="editTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editTenantModalLabel">Editar Tienda</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editTenantForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="editTenantId" name="id">
            <div class="mb-3">
              <label for="editTenantName" class="form-label">Nombre</label>
              <input type="text" class="form-control border border-1 p-2" id="editTenantName" name="name" required>
            </div>
            <div class="mb-3">
              <label for="editTenantSlug" class="form-label">Slug</label>
              <input type="text" class="form-control border border-1 p-2" id="editTenantSlug" name="slug" required>
            </div>
            <div class="mb-3">
              <label for="editTenantEmail" class="form-label">Email</label>
              <input type="email" class="form-control border border-1 p-2" id="editTenantEmail" name="email">
            </div>
            <div class="mb-3">
              <label for="editTenantLogo" class="form-label">Logo (URL)</label>
              <input type="text" class="form-control border border-1 p-2" id="editTenantLogo" name="logo">
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
  // Llenar modal para editar Tenant
  document.querySelectorAll('.btn-edit-tenant').forEach(button => {
    button.addEventListener('click', function () {
      document.getElementById('editTenantId').value = this.dataset.id;
      document.getElementById('editTenantName').value = this.dataset.name;
      document.getElementById('editTenantSlug').value = this.dataset.slug;
      document.getElementById('editTenantEmail').value = this.dataset.email;
      document.getElementById('editTenantLogo').value = this.dataset.logo;
    });
  });

  // Actualizar Tenant
  document.getElementById('editTenantForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    const tenantId = formData.get('id');
    fetch(`api/tenants/${tenantId}`, {
      method: 'POST', // Cambiar a 'PUT' si tu API lo requiere
      headers: {
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      alert('Tienda actualizada correctamente');
      window.location.reload();
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Ocurrió un error al actualizar la tienda');
    });
  });

  // Eliminar Tenant
  document.querySelectorAll('.btn-delete-tenant').forEach(button => {
    button.addEventListener('click', function () {
      const tenantId = this.dataset.id;
      fetch(`api/tenants/${tenantId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
      })
      .then(response => response.json())
      .then(data => {
        alert('Tienda eliminada correctamente');
        window.location.reload();
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al eliminar la tienda');
      });
    });
  });
</script>
@endpush
