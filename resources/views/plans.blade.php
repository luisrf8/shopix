@extends('layouts.app')

@section('title', 'Planes')

@section('content')
<div class="container-fluid py-2">
  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <!-- Header con título y botón -->
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3">PLANES</h6>
            <button class="btn btn-sm btn-light me-3" data-bs-toggle="modal" data-bs-target="#createPlanModal">
              + Agregar Plan
            </button>
          </div>
        </div>

        <!-- Grid de Cards -->
        <div class="card-body px-4 pb-4">
          <div class="row">
            @foreach($plans as $plan)
            <div class="col-md-4 mb-4">
              <div class="card h-100 shadow">
                <div class="card-body d-flex flex-column">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">{{ $plan->name }}</h5>
                    @if($plan->logo)
                      <img src="{{ $plan->logo }}" alt="{{ $plan->name }}" style="height:40px;">
                    @endif
                  </div>

                  <h6 class="text-muted">{{ $plan->service_type ?? 'Servicio' }}</h6>
                  <p><strong>Monto a pagar:</strong> <span class="text-dark">${{ number_format($plan->price,2) }}</span></p>
                  <p><strong>Duración:</strong> {{ $plan->duration_days }} días</p>
                    <h6>Características:</h6>
                    @if($plan->features && is_array($plan->features))
                    <div class="mb-3 d-flex flex-column gap-1">
                        @foreach($plan->features as $feature)
                            <span>✔ {{ $feature }}</span>
                        @endforeach
                    </div>

                @else
                    <span>No hay características registradas</span>
                @endif

                  <div class="mt-auto d-flex justify-content-between">
                    <button class="btn btn-primary btn-sm btn-edit-plan" 
                            data-id="{{ $plan->id }}"
                            data-name="{{ $plan->name }}"
                            data-price="{{ $plan->price }}"
                            data-logo="{{ $plan->logo }}"
                            data-duration="{{ $plan->duration_days }}"
                            data-features="{{ json_encode($plan->features) }}">
                      Editar
                    </button>
                    <button class="btn btn-danger btn-sm btn-delete-plan" data-id="{{ $plan->id }}">
                      Eliminar
                    </button>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Crear Plan -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createPlanForm" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Crear Nuevo Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="createPlanName" class="form-label">Nombre</label>
          <input type="text" class="form-control border border-1 p-2" id="createPlanName" name="name" required>
        </div>
        <div class="mb-3">
          <label for="createPlanPrice" class="form-label">Precio</label>
          <input type="number" step="0.01" class="form-control border border-1 p-2" id="createPlanPrice" name="price" required>
        </div>
        <div class="mb-3">
          <label for="createPlanDuration" class="form-label">Duración (días)</label>
          <input type="number" class="form-control border border-1 p-2" id="createPlanDuration" name="duration_days" required>
        </div>
        <div class="mb-3">
          <label for="createPlanLogo" class="form-label">Logo (URL)</label>
          <input type="text" class="form-control border border-1 p-2" id="createPlanLogo" name="logo">
        </div>
        <div class="mb-3">
          <label for="createPlanFeatures" class="form-label">Características (separadas por coma)</label>
          <textarea class="form-control border border-1 p-2" id="createPlanFeatures" name="features"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Editar Plan -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editPlanForm" class="modal-content">
      @csrf
      <input type="hidden" id="editPlanId" name="id">
      <div class="modal-header">
        <h5 class="modal-title">Editar Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="editPlanName" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="editPlanName" name="name" required>
        </div>
        <div class="mb-3">
          <label for="editPlanPrice" class="form-label">Precio</label>
          <input type="number" step="0.01" class="form-control" id="editPlanPrice" name="price" required>
        </div>
        <div class="mb-3">
          <label for="editPlanDuration" class="form-label">Duración (días)</label>
          <input type="number" class="form-control" id="editPlanDuration" name="duration_days" required>
        </div>
        <div class="mb-3">
          <label for="editPlanLogo" class="form-label">Logo (URL)</label>
          <input type="text" class="form-control" id="editPlanLogo" name="logo">
        </div>
        <div class="mb-3">
          <label for="editPlanFeatures" class="form-label">Características (separadas por coma)</label>
          <textarea class="form-control" id="editPlanFeatures" name="features"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Crear Plan
  document.getElementById('createPlanForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch(`/api/plans`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
      body: formData
    })
    .then(res => res.json())
    .then(() => { 
      alert('Plan creado'); 
      location.reload(); 
    })
    .catch(err => { console.error(err); alert('Error al crear'); });
  });

  // Abrir modal con datos de edición
  document.querySelectorAll('.btn-edit-plan').forEach(button => {
    button.addEventListener('click', function () {
      document.getElementById('editPlanId').value = this.dataset.id;
      document.getElementById('editPlanName').value = this.dataset.name;
      document.getElementById('editPlanPrice').value = this.dataset.price;
      document.getElementById('editPlanDuration').value = this.dataset.duration;
      document.getElementById('editPlanLogo').value = this.dataset.logo;
      document.getElementById('editPlanFeatures').value = JSON.parse(this.dataset.features || '[]').join(', ');
      new bootstrap.Modal(document.getElementById('editPlanModal')).show();
    });
  });

  // Guardar cambios al editar
document.getElementById('editPlanForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const planId = document.getElementById('editPlanId').value;

    const data = {
        name: document.getElementById('editPlanName').value,
        price: document.getElementById('editPlanPrice').value,
        duration_days: document.getElementById('editPlanDuration').value,
        logo: document.getElementById('editPlanLogo').value,
        features: document.getElementById('editPlanFeatures').value
    };

    fetch(`/api/plans/${planId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => { alert('Plan actualizado'); location.reload(); })
    .catch(err => { console.error(err); alert('Error al actualizar'); });
});



  // Eliminar plan
  document.querySelectorAll('.btn-delete-plan').forEach(button => {
    button.addEventListener('click', function () {
      if(!confirm("¿Eliminar este plan?")) return;
      const planId = this.dataset.id;
      fetch(`/api/plans/${planId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }
      })
      .then(res => res.json())
      .then(() => { alert('Plan eliminado'); location.reload(); })
      .catch(err => { console.error(err); alert('Error al eliminar'); });
    });
  });
</script>
@endpush
