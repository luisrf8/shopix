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
                    <div class="position-relative" style="width: 25rem; height: 25rem;">
                      <p class="text-info position-absolute top-0 end-0 m-2">
                        @if(isset($product->images) && count($product->images) > 0)
                          <button class="btn btn-danger btn-sm" onclick="confirmRemoveImage({{ $product->images[0]->id }})">Eliminar imagen</button>
                        @else
                          <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#addImageModal">Agregar imagen +</button>
                        @endif
                      </p>
                      <div class="icon icon-shape icon-xl shadow bg-transparent text-center border border-1 border-info text-info border-radius-lg w-100 h-100">
                        @if(isset($product->images) && count($product->images) > 0)
                          <img src="{{ asset('storage/' . $product->images[0]->path) }}" alt="Imagen del producto" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                        @else
                          <i class="material-symbols-rounded text-dark" style="font-size: 5rem;">photo_camera</i>
                        @endif
                      </div>
                    </div>
                    <!-- Modal para agregar imagen -->
                    <div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <form id="addImageForm" method="POST" action="{{ route('product.addImage', $product->id) }}" enctype="multipart/form-data">
                          @csrf
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="addImageModalLabel">Agregar imagen</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <label for="image" class="form-label">Seleccionar imagen</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- Product details -->
                    <div class="mx-4">
                      <!-- <div class="card-header">{{ $product->name }}</div> -->
                      <h2><strong>{{ $product->name }}</strong></h2>
                      <p><strong>Categoría:</strong> {{ $product->category->name }}</p>
                      <p><strong>Descripción:</strong> {{ $product->description }}</p>
                      <p><strong>Tallas:</strong>
                        <ul>
                          @foreach ($product->variants as $variant)
                              <li>Talla: {{ $variant->size }} - Precio: {{ $variant->price }} $ - {{$variant->stock}} unidades disponibles</li>
                          @endforeach
                        </ul>
                      </p>
                      <!-- <p><strong>Categoría:</strong> {{ $product->category->name }}</p> -->
                         <!-- Action Buttons -->
                      <div class="mt-4">
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
                    <span aria-hidden="true" class="btn-close" data-bs-dismiss="modal"></span>
                  </div>
                  <div class="modal-body">
                  <form id="editProductForm"enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="productName">Nombre</label>
                            <input type="text" class="form-control border border-1 p-2" id="productName" name="name" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="productDescription">Descripción</label>
                            <input class="form-control border border-1 p-2" id="productDescription" name="description" rows="3" value="{{ old('description', $product->description) }}" required></input>
                        </div>
                        <div class="form-group mb-4">
                            <label for="productCategory">Categoría</label>
                            <select class="form-control border border-1 p-2" id="productCategory" name="category" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ $category->id == old('category', $product->category_id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveChangesBtn">Guardar Cambios</button>
                    </form>
                    <div class="form-group">
                      <label for="productVariants">Variedades</label>
                      <div id="variantContainer"></div>
                      <button type="button" class="btn btn-secondary mt-3" id="addVariantBtn">Agregar Variante</button>
                      <button type="button" class="btn btn-primary mt-3" id="saveVariantsBtn">Guardar Variantes Creadas</button>
                    </div>
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
  </div>
</main>
<!-- Core JS Files -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>


<script>
  document.querySelectorAll('.thumbnail img').forEach(img => {
    img.addEventListener('click', function() {
      document.getElementById('mainImage').src = this.src;
    });
  });
  document.getElementById('addVariantBtn').addEventListener('click', function () {
    const variantContainer = document.getElementById('variantContainer');

    // Crear un nuevo div para la variante
    const variantDiv = document.createElement('div');
    variantDiv.classList.add('col', 'mb-3');

    // Crear contenedor para inputs
    const inputContainer = document.createElement('div');
    inputContainer.classList.add('input-group', 'gap-4');

    // Input para el nombre de la variante
    const variantInput = document.createElement('input');
    variantInput.type = 'text';
    variantInput.placeholder = 'Talla';
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
    deleteBtn.classList.add('btn', 'btn-danger', 'mt-2', 'ms-auto');

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

function editProduct() {
  // Obtener los datos del producto desde el DOM o una llamada AJAX
  const productData = {
    variants: @json($product->variants) // Convertir a JSON los datos de las variantes
  };

  // Precargar las variantes
  const variantContainer = document.getElementById('variantContainer');
  variantContainer.innerHTML = ''; // Limpiar variantes previas
  productData.variants.forEach(variant => {
    const variantDiv = document.createElement('div');
    variantDiv.classList.add('row', 'mb-3');
    variantDiv.innerHTML = `
      <div class="col">
        <label for="Nombre">Talla</label>
        <input type="text" class="form-control border border-1 p-2" value="${variant.size}" placeholder="Nombre" name="variantName[]">
      </div>
      <div class="col">
        <label for="Precio">Precio USD</label>
        <input type="number" class="form-control border border-1 p-2" value="${variant.price}" placeholder="Precio" name="variantPrice[]">
      </div>
      <div class="col">
        <label for="Stock">Stock</label>
        <input type="number" class="form-control border border-1 p-2" value="${variant.stock}" placeholder="Stock" name="variantStock[]">
      </div>
      <div class="col pt-2">
        <button type="button" class="btn btn-primary mt-4 editVariantBtn" data-id="${variant.id}">Editar</button>
      </div>
    `;
    variantContainer.appendChild(variantDiv);
  });

  // Agregar evento al botón "Editar"
  document.querySelectorAll('.editVariantBtn').forEach(button => {
    button.addEventListener('click', function () {
      const variantId = this.getAttribute('data-id');
      const variantRow = this.closest('.row');
      const size = variantRow.querySelector('input[name="variantName[]"]').value;
      const price = variantRow.querySelector('input[name="variantPrice[]"]').value;
      const stock = variantRow.querySelector('input[name="variantStock[]"]').value;

      // Realizar una solicitud AJAX para actualizar la variante
      fetch(`/api/variants/${variantId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ size, price, stock })
      })
        .then(response => {
          if (response.ok) {
            alert('Variante actualizada exitosamente.');
            window.location.reload()
          } else {
            throw new Error('Error al actualizar la variante.');
          }
        })
        .catch(error => {
          console.error(error);
          alert('Hubo un problema al actualizar la variante.');
        });
    });
  });
}
function confirmRemoveImage(imageId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
      fetch(`/api/product/remove-image/${imageId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Imagen eliminada correctamente');
          location.reload();
        } else {
          alert('Error al eliminar la imagen');
        }
      })
      .catch(error => console.error('Error:', error));
    }
  }
document.getElementById('editProductForm').addEventListener('submit', function(event) {
  event.preventDefault(); // Evitar que se recargue la página
  console.log("Formulario enviado");
    // Crear un objeto con los datos del formulario
    let formData = new FormData(this);

    const productId = document.querySelector('.card').getAttribute('data-product-id');
    console.log("productId", productId);

    // Realizar la solicitud fetch con el body en formato JSON
    fetch(`/api/products/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').value,
        },
        body: formData, // Convertir el objeto a JSON
    })
    .then(response => {
        if (response.status === 201) { // Valida el código de estado HTTP
          alert('Producto actualizado correctamente');
          window.location.reload();
        } else {
          throw new Error('Error al crear la categoría');
        }
      })
    .catch(error => {
        console.error('Error:', error);
    });
});
  // Añadir un evento al formulario para cuando se envíe
  document.getElementById('addImageForm').addEventListener('submit', function(event) {
    event.preventDefault();  // Evitar el envío del formulario para inspeccionar los datos primero

    // Crear un FormData con los datos del formulario
    const formData = new FormData(this);

    // Registrar los datos del FormData en la consola
    for (let [key, value] of formData.entries()) {
      console.log(key, value);
    }

    // Si quieres, puedes enviar el formulario aquí usando fetch o axios si no quieres usar el envío tradicional
    // fetch(this.action, {
    //   method: 'POST',
    //   body: formData,
    // }).then(response => {
    //   return response.json();
    // }).then(data => {
    //   console.log(data);
    // }).catch(error => {
    //   console.log(error);
    // });

    // Si todo es correcto, puedes permitir que el formulario se envíe normalmente
    // this.submit();  // Descomentar esta línea para enviar el formulario
  });

</script>


</body>

</html>