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
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">
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
        <div class="col-lg-12">
          <!-- Buscador -->
          <div class="mb-3 px-2">
            <input type="text" id="searchCategory" class="w-25 form-control border border-1 p-2 bg-white" placeholder="Buscar categoría...">
          </div>
          <!-- Carrusel scrollable -->
          <div id="categoriesContainer" class="d-flex overflow-auto gap-3 px-2 py-3" style="scroll-snap-type: x mandatory;">
            <div class=" flex-shrink-0" style="width: 200px; scroll-snap-align: start;">
              <a  href="/products" class="text-decoration-none">
                <div class="card h-100">
                  <div class="card-header mx-3 p-3 text-center">
                    <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                      <i class="material-symbols-rounded opacity-10">category</i>
                    </div>
                  </div>
                  <div class="card-body pt-0 p-3 text-center">
                    <h6 class="text-center mb-0 opacity-9">Todos</h6>
                    <span class="text-xs"></span>
                  </div>
                </div>
              </a>
            </div>
            @foreach($categories as $category)
              <div class="category-item flex-shrink-0" style="width: 200px; scroll-snap-align: start;" data-name="{{ strtolower($category['name']) }}">
                <a href="{{ route('products.byCategory', $category->id) }}" class="text-decoration-none">
                  <div class="card h-100">
                    <div class="card-header mx-3 p-3 text-center">
                      <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">category</i>
                      </div>
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0 opacity-9">{{ $category['name'] }}</h6>
                      <span class="text-xs">{{ $category['description'] }}</span>
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="row">
      <div class="col-md-12 mt-4">
  <div class="">
    <div class="pb-0 px-3">
      <h6 class="mb-0">Productos</h6>
    </div>
    <div class="py-1 px-3 text-end" onclick="getSucursales()">
      <a class="nav-link text-dark" href="/createProduct">
        <label>
          + Agregar Producto
        </label>
      </a>
    </div>
    <!-- Modal para crear producto -->
    <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createProductModalLabel">Crear Producto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Formulario para crear el producto -->
            <form id="createProductForm" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                  <label for="categorySelector" class="form-label">Categoría (Sucursal)</label>
                  <select id="categorySelector" name="category_id" class="form-control border border-1 p-2" required>
                      <option value="">Selecciona una categoría</option>
                  </select>
              </div>
              <div class="mb-3">
                <label for="productName" class="form-label">Nombre del producto</label>
                <input type="text" class="form-control border border-1 p-2" id="productName" name="name" required>
              </div>
              <div class="mb-3">
                <label for="productDescription" class="form-label">Descripción</label>
                <textarea class="form-control border border-1 p-2" id="productDescription" name="description" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label for="productPrice" class="form-label">Precio</label>
                <input type="number" class="form-control border border-1 p-2" id="productPrice" name="price" step="0.01" required>
              </div>
              <div class="mb-3">
                <label for="productImages" class="form-label">Imágenes</label>
                <div class="form-control border border-1 p-2">
                  <input type="file" class="" id="productImages" name="images[]" multiple>
                </div>
              </div>
              <div class="d-flex flex-row-reverse">
                <button type="submit" class="btn btn-info">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin Modal para crear producto -->
    <div class="px-3">
      <div class="mb-3">
        <input type="text" id="searchProduct" class="w-25 form-control border border-1 p-2 bg-white" placeholder="Buscar producto...">
      </div>
      <div class="row">
        <!-- Buscador -->
      @foreach($productItems as $product)
        <div class="product-item col-md-4 mb-4" data-name="{{ strtolower($product->name) }}">
          <div class="card p-4 d-flex flex-row" style="height: 10rem;">
              <a href="{{ route('productItem', $product->id) }}" class="icon icon-shape icon-xl shadow bg-transparent text-center border border-1 border-black text-info border-radius-lg" style="width: 100px; height: 100px;">
                  @if(isset($product->images) && count($product->images) > 0)
                      <img src="{{ asset('storage/' . $product->images[0]->path) }}" alt="Imagen del producto" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                  @else
                      <i class="material-symbols-rounded text-dark">photo_camera</i>
                  @endif
              </a>
              <div class="d-flex flex-column mx-3">
                <h6 class="mb-2 text-sm">{{ $product->name }}</h6>
                @foreach ($product->variants as $variant)
                  <span class="mb-2 text-sm">
                    Talla: {{ $variant->size }} - {{ $variant->price }} $ - 
                  <span class="{{ $variant->stock < 1 ? 'text-danger' : ($variant->stock < 5 ? 'text-warning' : 'text-success') }}">{{ $variant->stock }} unidades</span>
                  </span>
                @endforeach
                    <!-- Mostrar las tallas -->
              </div>
              <div class="ms-auto text-end">
                <a href="{{ route('productItem', $product->id) }}" class="btn btn-link text-dark" ><i class="material-symbols-rounded text-sm">edit</i>Editar</a>
              </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
      </div>
    </div>
  </main>
<!-- Core JS Files -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

  <script>
    document.getElementById('createProductForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Evita el envío normal del formulario

      let formData = new FormData(this); // Crear un FormData con los datos del formulario

      fetch('api/create-product', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
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

    function getSucursales() {
      fetch('api/categories', {
          method: 'GET',
          headers: {
              'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
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
  document.getElementById('searchCategory').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const items = document.querySelectorAll('.category-item');

    items.forEach(item => {
      const name = item.getAttribute('data-name');
      if (name.includes(searchValue)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  });
  document.getElementById('searchProduct').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const items = document.querySelectorAll('.product-item');

    items.forEach(item => {
      const name = item.getAttribute('data-name');
      if (name.includes(searchValue)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  });
  </script>

</body>

</html>