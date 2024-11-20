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
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    @extends('layouts.navbar')
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('layouts.head')
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="row">
      <div class="col-md-12 mt-4">
  <div class="">
    <div class="pb-0 px-3">
      <a href="{{ route('products') }}">
        <h6 class="mb-0"> <i class="material-symbols-rounded opacity-10">arrow_back_ios_new</i> Volver</h6>
      </a>
    </div>
    <div class="pt-4">
      <div class="row">
      <div class="container">
          <div class="row">
            <div class="col-md-12">
              <!-- <div class="card"> -->
              <div class="card" data-product-id="{{ $product->id }}">
                  <div class="card-body d-flex flex-row">
                    
                    <!-- Column for thumbnails -->
                    <div class="d-flex flex-column me-3 gap-2">
                      <div class="icon icon-shape icon-xl shadow bg-transparent text-center border border-1 border-info text-info border-radius-lg" style="width: 100px; height: 100px;">
                        <i class="material-symbols-rounded text-dark">photo_camera</i>
                        <!-- <img src="{{ $product->image1 }}" alt="{{ $product->name }} image 1" style="width: 5rem; height: 5rem; object-fit: cover; cursor: pointer; border: 1px solid #ddd;"> -->
                      </div>
                      <div class="icon icon-shape icon-xl shadow bg-transparent text-center border border-1 border-info text-info border-radius-lg" style="width: 100px; height: 100px;">
                        <i class="material-symbols-rounded text-dark">photo_camera</i>
                        <!-- <img src="{{ $product->image2 }}" alt="{{ $product->name }} image 2" style="width: 5rem; height: 5rem; object-fit: cover; cursor: pointer; border: 1px solid #ddd;"> -->
                      </div>
                      <div class="icon icon-shape icon-xl shadow bg-transparent text-center border border-1 border-info text-info border-radius-lg" style="width: 100px; height: 100px;">
                        <i class="material-symbols-rounded text-dark">photo_camera</i>
                        <!-- <img src="{{ $product->image3 }}" alt="{{ $product->name }} image 3" style="width: 5rem; height: 5rem; object-fit: cover; cursor: pointer; border: 1px solid #ddd;"> -->
                      </div>
                      <div class="text-info">
                        <p class="text-info">Add img +</p>
                        <!-- <img src="{{ $product->image2 }}" alt="{{ $product->name }} image 2" style="width: 5rem; height: 5rem; object-fit: cover; cursor: pointer; border: 1px solid #ddd;"> -->
                      </div>
                    </div>
                    <!-- Main image display -->
                    <div class="icon icon-shape icon-xl shadow bg-transparent text-center border border-1 border-info text-info border-radius-lg" style="width: 25rem; height: 25rem;">
                      <i class="material-symbols-rounded text-dark">photo_camera</i>
                      <!-- <img id="mainImage" src="{{ $product->image1 }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;"> -->
                    </div>
                    <!-- Product details -->
                    <div class="mx-4">
                      <!-- <div class="card-header">{{ $product->name }}</div> -->
                      <h2><strong>{{ $product->name }}</strong></h2>
                      <p><strong>Descripción:</strong> {{ $product->description }}</p>
                      <p><strong>Precio:</strong> ${{ $product->price }}</p>
                      <p><strong>Tallas:</strong> S / M / L / XL</p>
                      <!-- <p><strong>Categoría:</strong> {{ $product->category->name }}</p> -->
                         <!-- Action Buttons -->
                      <div class="mt-4">
                        <!-- <button class="btn btn-info me-2" onclick="addToCart({{ $product->id }})">Agregar al Carrito +</button>
                        <button class="btn btn-info me-2" onclick="buyNow({{ $product->id }})">Comprar</button> -->
                        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="editProduct()">Editar</button>
                        <button class="btn btn-primary" onclick="deleteProduct({{ $product->id }})">Eliminar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                      <span aria-hidden="true">&times;</span>
                  </div>
                  <div class="modal-body">
                    <form id="editProductForm">
                      <div class="form-group">
                        <label for="productName">Nombre</label>
                        <input type="text" class="form-control border border-1 p-2" id="productName" required>
                      </div>
                      <div class="form-group">
                        <label for="productDescription">Descripción</label>
                        <textarea class="form-control border border-1 p-2" id="productDescription" rows="3" required></textarea>
                      </div>
                      <div class="form-group">
                        <label for="productPrice">Precio</label>
                        <input type="number" class="form-control border border-1 p-2" id="productPrice" required>
                      </div>
                      <div class="form-group">
                        <label for="productCategory">Categoría</label>
                        <select class="form-control border border-1 p-2" id="productCategory" required>
                          <!-- Aquí puedes llenar las categorías disponibles -->
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="productImages">Imágenes</label>
                        <input type="file" class="form-control-file border-1 p-2" id="productImages" multiple>
                        <div id="imagePreview"></div> <!-- Para previsualizar imágenes -->
                      </div>
                      <div class="form-group">
                          <label for="productVariants">Variedades</label>
                          <div class="row" id="variantContainer">
                            @csrf
                              <!-- Aquí se agregarán dinámicamente las variantes -->
                          </div>
                          <button type="button" class="btn btn-secondary mt-3" id="addVariantBtn">Agregar Variante</button>
                          <button type="button" class="btn btn-primary mt-3" id="saveVariantsBtn">Guardar Variantes</button>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="saveChangesBtn">Guardar Cambios</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Modal -->
          </div>
      </div>
    </div>
  </div>
      </div>
      <footer class="footer py-4  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-symbols-rounded py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Material UI Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-symbols-rounded">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info active" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-info px-3 mb-2" data-class="bg-gradient-info" onclick="sidebarType(this)">Dark</button>
          <button class="btn bg-gradient-info px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
          <button class="btn bg-gradient-info px-3 mb-2  active ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
        <a class="btn bg-gradient-info w-100" href="https://www.creative-tim.com/product/material-dashboard-pro">Free Download</a>
        <a class="btn btn-outline-dark w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard">View documentation</a>
        <div class="w-100 text-center">
          <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
          <h6 class="mt-3">Thank you for sharing!</h6>
          <a href="https://twitter.com/intent/tweet?text=Check%20Material%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/material-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
          </a>
        </div>
      </div>
    </div>
  </div>
<!-- Core JS Files -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->

<script>
  document.querySelectorAll('.thumbnail img').forEach(img => {
    img.addEventListener('click', function() {
      document.getElementById('mainImage').src = this.src;
    });
  });
  function editProduct() {

  }
  document.getElementById('addVariantBtn').addEventListener('click', function () {
    const variantContainer = document.getElementById('variantContainer');

    // Crear un nuevo div para la variante
    const variantDiv = document.createElement('div');
    variantDiv.classList.add('col-6', 'mb-3');

    // Crear contenedor para inputs
    const inputContainer = document.createElement('div');
    inputContainer.classList.add('input-group', 'gap-2');

    // Input para el nombre de la variante
    const variantInput = document.createElement('input');
    variantInput.type = 'text';
    variantInput.placeholder = 'Nombre';
    variantInput.classList.add('form-control', 'border', 'border-1', 'p-2');
    variantInput.name = 'size';

    // Input para el precio
    const priceInput = document.createElement('input');
    priceInput.type = 'number';
    priceInput.placeholder = 'Precio';
    priceInput.classList.add('form-control', 'border', 'border-1', 'p-2');
    priceInput.name = 'price';

    // Input para el stock
    const stockInput = document.createElement('input');
    stockInput.type = 'number';
    stockInput.placeholder = 'Stock';
    stockInput.classList.add('form-control', 'border', 'border-1', 'p-2');
    stockInput.name = 'stock';

    // Botón para eliminar la variante
    const deleteBtn = document.createElement('button');
    deleteBtn.innerText = 'Eliminar';
    deleteBtn.classList.add('btn', 'btn-danger', 'mt-2');

    // Funcionalidad para eliminar la variante
    deleteBtn.addEventListener('click', function () {
        variantContainer.removeChild(variantDiv);
    });

    // Agregar inputs al contenedor de inputs
    inputContainer.appendChild(variantInput);
    inputContainer.appendChild(priceInput);
    inputContainer.appendChild(stockInput);

    // Agregar los elementos al div de variante
    variantDiv.appendChild(inputContainer);
    variantDiv.appendChild(deleteBtn);

    // Agregar la nueva variante al contenedor
    variantContainer.appendChild(variantDiv);
});

// Función para guardar variantes
document.getElementById('saveVariantsBtn').addEventListener('click', function () {
    const variantContainer = document.getElementById('variantContainer');
    // const productId = document.querySelector('input[name="product_id"]').value;
    // Obtener el id del producto desde la tarjeta
    const productId = document.querySelector('.card').getAttribute('data-product-id');
    const variants = [];

    // Recorrer todas las variantes creadas
    variantContainer.querySelectorAll('.input-group').forEach(inputGroup => {
        const size = inputGroup.querySelector('input[name="size"]').value;
        const price = inputGroup.querySelector('input[name="price"]').value;
        const stock = inputGroup.querySelector('input[name="stock"]').value;

        // Validar que los campos no estén vacíos
        if (size && price && stock) {
            variants.push({ size, price, stock });
        }
    });

    // Enviar las variantes al servidor mediante AJAX
    if (variants.length > 0) {
        fetch('/api/variants/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ product_id: productId, variants }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Variantes guardadas exitosamente.');
                    location.reload();
                } else {
                    alert('Error al guardar variantes.');
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        alert('Por favor, completa todos los campos antes de guardar.');
    }
});

// Funcionalidad para agregar variantes
$('#addVariantBtn').on('click', function() {
  $('#variantContainer').append(`
    <div class="form-group variant">
      <input type="text" class="form-control border border-1 p-2" placeholder="Tamaño" required>
      <input type="number" class="form-control border border-1 p-2" placeholder="Precio" required>
      <input type="number" class="form-control border border-1 p-2" placeholder="Stock" required>
      <button type="button" class="btn btn-danger removeVariantBtn">Eliminar</button>
    </div>
  `);
});

// Eliminar variante
$(document).on('click', '.removeVariantBtn', function() {
  $(this).closest('.variant').remove();
});

// Guardar cambios
$('#saveChangesBtn').on('click', function() {
  // Crear un objeto FormData para enviar el formulario
  const formData = new FormData(document.getElementById('editProductForm'));

  // Añadir las variantes al FormData
  $('#variantContainer .variant').each(function() {
    const size = $(this).find('input').eq(0).val();
    const price = $(this).find('input').eq(1).val();
    const stock = $(this).find('input').eq(2).val();
    formData.append('variants[]', JSON.stringify({ size, price, stock }));
  });

  // Enviar la solicitud para actualizar el producto
  $.ajax({
    url: '/api/products/' + productId, // Asegúrate de incluir el ID del producto
    method: 'PUT',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      alert('Producto actualizado con éxito.');
      $('#editProductModal').modal('hide');
      location.reload(); // Recargar la página para ver los cambios
    },
    error: function(err) {
      alert('Error al actualizar el producto.');
    }
  });
});

</script>


</body>

</html>