@extends('layouts.app')

@section('title', 'Crear Tenant')

@section('content')
        <div class="container">
            <div class="card shadow-sm my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark text-white shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-center align-items-center">
                        <h1 class="text-white">Crear Nuevo Tenant</h1>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Tenant</label>
                            <input type="text" name="name" id="name" class="form-control border border-radius-lg p-2" placeholder="Ej: Mi Empresa" required>
                        </div>

                        {{-- Slug --}}
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug / URL de Landing</label>
                            <input type="text" name="slug" id="slug" class="form-control border border-radius-lg p-2" placeholder="/ejemplo-mi-empresa" required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo de contacto</label>
                            <input type="email" name="email" id="email" class="form-control border border-radius-lg p-2" placeholder="correo@empresa.com" required>
                        </div>
                        
                        <div class="row mb-3">
                            {{-- Logo --}}
                            <div class="col-12 col-md-6">
                                <label for="logo" class="form-label">Logo (PNG o SVG)</label>
                                <div class="d-flex align-items-center gap-3">
                                    <img id="logo-preview" src="#"
                                        alt="Vista previa del logo"
                                        class="img-fluid rounded shadow-sm border d-none p-2"
                                        style="max-height: 100px; max-width: 100px;">
                                    <input type="file" name="logo" id="logo"
                                        class="form-control border border-radius-lg p-2"
                                        accept=".png,.svg">
                                </div>
                            </div>
                            {{-- Colores --}}
                            <div class="row col-12 col-md-6">
                                <div class="col-md-4">
                                    <label for="color_primary" class="form-label">Color Primario</label>
                                    <input type="color" name="color_primary" id="color_primary" class="form-control border border-radius-lg p-2 border border-radius-lg p-2 form-control border border-radius-lg p-2 border border-radius-lg p-2-color h-50" value="#0d6efd">
                                </div>
                                <div class="col-md-4">
                                    <label for="color_secondary" class="form-label">Color Secundario</label>
                                    <input type="color" name="color_secondary" id="color_secondary" class="form-control border border-radius-lg p-2 form-control border border-radius-lg p-2-color h-50" value="#6c757d">
                                </div>
                                <div class="col-md-4">
                                    <label for="color_accent" class="form-label">Color Acento</label>
                                    <input type="color" name="color_accent" id="color_accent" class="form-control border border-radius-lg p-2 form-control border border-radius-lg p-2-color h-50" value="#ffc107">
                                </div>
                            </div>

                        </div>
                        
                        {{-- Plan --}}
                        <div class="mb-3">
                            <label for="plan_id" class="form-label">Plan</label>
                            <select name="plan_id" id="plan_id" class="form-control border border-radius-lg p-2" required>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->price }}</option>
                                @endforeach
                            </select>
                        </div>



                        {{-- Roles y Usuarios iniciales --}}
                        <div class="mb-3">
                            <label class="form-label">Usuarios iniciales</label>
                            <div class="accordion" id="accordionRoles">

                                {{-- Owner --}}
                                <div class="accordion-item border border-radius-lg p-2">
                                    <h2 class="accordion-header" id="headingOwner">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOwner" aria-controls="collapseOwner">
                                            👑 Owner
                                        </button>
                                    </h2>
                                    <div id="collapseOwner" class="accordion-collapse collapse show" aria-labelledby="headingOwner" data-bs-parent="#accordionRoles">
                                        <div class="accordion-body">
                                            <div class="mb-2">
                                                <label for="owner_name" class="form-label">Nombre</label>
                                                <input type="text" name="users[owner][name]" id="owner_name" class="form-control border border-radius-lg p-2" placeholder="Nombre del Owner">
                                            </div>
                                            <div class="mb-2">
                                                <label for="owner_email" class="form-label">Correo</label>
                                                <input type="email" name="users[owner][email]" id="owner_email" class="form-control border border-radius-lg p-2" placeholder="owner@empresa.com">
                                            </div>
                                            <div class="mb-2">
                                                <label for="owner_password" class="form-label">Contraseña</label>
                                                <div class="input-group gap-2">
                                                    <input type="password" name="users[owner][password]" id="owner_password" class="form-control border border-radius-lg p-2" placeholder="********">
                                                        <button type="button" class="input-group-text toggle-password mx-5" data-target="owner_password">
                                                            👁️
                                                        </button>
                                                        <button type="button" class="input-group-text copy-password mx-3" data-target="owner_password">
                                                            📋
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Admin --}}
                                <div class="accordion-item border border-radius-lg p-2 mt-2">
                                    <h2 class="accordion-header" id="headingAdmin">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                                            🛠️ Admin
                                        </button>
                                    </h2>
                                    <div id="collapseAdmin" class="accordion-collapse collapse" aria-labelledby="headingAdmin" data-bs-parent="#accordionRoles">
                                        <div class="accordion-body">
                                            <div class="mb-2">
                                                <label for="admin_name" class="form-label">Nombre</label>
                                                <input type="text" name="users[admin][name]" id="admin_name" class="form-control border border-radius-lg p-2" placeholder="Nombre del Admin">
                                            </div>
                                            <div class="mb-2">
                                                <label for="admin_email" class="form-label">Correo</label>
                                                <input type="email" name="users[admin][email]" id="admin_email" class="form-control border border-radius-lg p-2" placeholder="admin@empresa.com">
                                            </div>
                                            <div class="mb-2">
                                                <label for="admin_password" class="form-label">Contraseña</label>
                                                <div class="input-group">
                                                    <input type="password" name="users[admin][password]" id="admin_password" class="form-control border border-radius-lg p-2" placeholder="********">
                                                        <button type="button" class="input-group-text toggle-password mx-5" data-target="admin_password">
                                                            👁️
                                                        </button>
                                                        <button type="button" class="input-group-text copy-password mx-3" data-target="admin_password">
                                                            📋
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Vendor --}}
                                <div class="accordion-item border border-radius-lg p-2 mt-2">
                                    <h2 class="accordion-header" id="headingVendor">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVendor" aria-expanded="false" aria-controls="collapseVendor">
                                            🛒 Vendor
                                        </button>
                                    </h2>
                                    <div id="collapseVendor" class="accordion-collapse collapse" aria-labelledby="headingVendor" data-bs-parent="#accordionRoles">
                                        <div class="accordion-body">
                                            <div class="mb-2">
                                                <label for="vendor_name" class="form-label">Nombre</label>
                                                <input type="text" name="users[vendor][name]" id="vendor_name" class="form-control border border-radius-lg p-2" placeholder="Nombre del Vendor">
                                            </div>
                                            <div class="mb-2">
                                                <label for="vendor_email" class="form-label">Correo</label>
                                                <input type="email" name="users[vendor][email]" id="vendor_email" class="form-control border border-radius-lg p-2" placeholder="vendor@empresa.com">
                                            </div>

                                            <div class="mb-2">
                                                <label for="vendor_password" class="form-label">Contraseña</label>
                                                <div class="input-group">
                                                    <input type="password" name="users[vendor][password]" id="vendor_password" class="form-control border border-radius-lg p-2" placeholder="********">
                                                        <button type="button" class="input-group-text toggle-password mx-5" data-target="vendor_password">
                                                            <i class="material-symbols-rounded opacity-5">hide_source</i>
                                                            <i class="material-symbols-rounded opacity-5">remove_red_eye</i>
                                                        </button>
                                                        <button type="button" class="input-group-text copy-password mx-3" data-target="vendor_password">
                                                            📋
                                                        </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <small class="text-muted">Recomendamos anotar en un lugar seguro las credenciales asociadas.</small>
                            <small class="text-muted">Los usuarios ingresados serán creados y vinculados al tenant automáticamente.</small>
                        </div>


                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">Crear Tenant</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Mostrar / ocultar contraseña
    document.querySelectorAll(".toggle-password").forEach(btn => {
        btn.addEventListener("click", () => {
            const targetId = btn.getAttribute("data-target");
            const input = document.getElementById(targetId);
            if (input.type === "password") {
                input.type = "text";
                btn.textContent = "🙈";
            } else {
                input.type = "password";
                btn.textContent = "👁️";
            }
        });
    });

    // Copiar contraseña al portapapeles
    document.querySelectorAll(".copy-password").forEach(btn => {
        btn.addEventListener("click", async () => {
            const targetId = btn.getAttribute("data-target");
            const input = document.getElementById(targetId);
            try {
                await navigator.clipboard.writeText(input.value);
                btn.textContent = "✅";
                setTimeout(() => btn.textContent = "📋", 1500);
            } catch (err) {
                alert("No se pudo copiar la contraseña");
            }
        });
    });

    // Vista previa del logo
    const logoInput = document.getElementById("logo");
    const logoPreview = document.getElementById("logo-preview");

    logoInput.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                logoPreview.src = e.target.result;
                logoPreview.classList.remove("d-none");
            };
            reader.readAsDataURL(file);
        } else {
            logoPreview.src = "#";
            logoPreview.classList.add("d-none");
        }
    });
});
</script>
@endpush


