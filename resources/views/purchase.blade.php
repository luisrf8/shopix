<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Flujo de Compra</title>
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
  <style>
/* Indicador de selección (viñeta) */
.indicator {
    width: 20px;
    height: 20px;
    background-color: transparent;
    border: 2px solid #ccc;
    border-radius: 50%;
    transition: background-color 0.3s, border-color 0.3s;
}

/* Cambiar el estado de la viñeta si está seleccionado */
input[type="checkbox"]:checked + .position-absolute {
    background-color: #26a69a; /* Verde */
    border-color: #26a69a;
}

</style>
</head>
<body class="g-sidenav-show  bg-gray-100">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    @extends('layouts.navbar')
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

  @include('layouts.head')

    <div class="m-5">
        <h1>Flujo de Compra</h1>
        <form id="purchaseForm">
        @csrf

<!-- Paso 1: Selección del Ítem -->
<div id="step1">
    <h4>Paso 1: Selecciona uno o más productos.</h4>
    
    <!-- Input de Búsqueda -->
    <div class="mb-3">
        <input 
            type="text" 
            id="searchInput" 
            class="form-control border border-1 p-2 bg-white" 
            placeholder="Buscar producto..." 
            onkeyup="filterProducts()">
    </div>
    
    <div id="itemSelector" class="row row-cols-1 row-cols-md-3 g-3">
        @foreach ($productItems as $item)
            <div class="col position-relative product-item" data-name="{{ strtolower($item->name) }}">
                <label class="card h-100" for="item_{{ $item->id }}" style="cursor: pointer;">
                    <input type="checkbox" class="form-check-input d-none" id="item_{{ $item->id }}" value="{{ $item->id }}" name="selectedItems[]">
                    <div class="position-absolute top-0 end-0 m-2 indicator" id="indicator_{{ $item->id }}"></div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ $item->description }}</p>
                    </div>
                </label>
            </div>
        @endforeach
    </div>
    
    <button type="button" class="btn btn-success mt-3" id="toStep2" disabled>Siguiente</button>
</div>

            <!-- Paso 2: Selección de Variante y Cantidad -->
            <div id="step2" class="d-none">
                <h4>Paso 2: Selecciona Variante y Cantidad</h4>
                <div id="variantContainer"></div>
                <button type="button" class="btn btn-secondary mt-3" id="toStep1">Atrás</button>
                <button type="button" class="btn btn-success mt-3" id="toStep3" disabled>Siguiente</button>
            </div>

            <!-- Paso 3: Selección de Proveedores -->
            <div id="step3" class="d-none">
                <h4>Paso 3: Selecciona Proveedor</h4>
                <div id="providerSelector" class="row row-cols-1 row-cols-md-3 g-3">
                    @foreach ($providers as $provider)
                        <div class="col position-relative product-item" data-name="{{ strtolower($provider->name) }}">
                            <label class="card h-100" for="provider_{{ $provider->id }}" style="cursor: pointer;">
                                <input type="checkbox" class="form-check-input d-none" id="provider_{{ $provider->id }}" value="{{ $provider->id }}" name="selectedProviders[]">
                                <div class="position-absolute top-0 end-0 m-2 indicator" id="indicator_{{ $provider->id }}"></div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $provider->name }}</h5>
                                    <p class="card-text">{{ $provider->description }}</p>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-secondary mt-3" id="backToStep2">Atrás</button>
                <button type="button" class="btn btn-success mt-3" id="toStep4" disabled>Siguiente</button>
            </div>

            <!-- Paso 4: Confirmación -->
            <div id="step4" class="d-none">
                <h4>Paso 4: Confirmación</h4>
                <div id="providerContainer"></div>
                <button type="button" class="btn btn-secondary mt-3" id="backToStep3">Atrás</button>
                <button type="button" class="btn btn-success mt-3" id="createOrder">Continuar</button>
            </div>
        </form>
    </div>
  </main>

<!-- Core JS Files -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
        // document.querySelectorAll('#itemSelector input[type="checkbox"]').forEach((checkbox) => {
        //     checkbox.addEventListener('change', function () {
        //         const checkedBoxes = document.querySelectorAll('#itemSelector input[type="checkbox"]:checked');
        //         console.log("checkboxes", checkedBoxes.length)
        //         document.getElementById('toStep2').disabled = checkedBoxes.length === 0; // Habilitar si hay al menos uno seleccionado
        //     });
        // });

        document.addEventListener('DOMContentLoaded', function () {
            var itemsSelected= [];
            const itemSelector = document.getElementById('itemSelector');
            const toStep2 = document.getElementById('toStep2');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const toStep1 = document.getElementById('toStep1');
            const backToStep2 = document.getElementById('backToStep2');
            const toStep3 = document.getElementById('toStep3');
            const backToStep3 = document.getElementById('backToStep3');
            const step3 = document.getElementById('step3');
            const supplierSelector = document.getElementById('supplierSelector');
            const step4 = document.getElementById('step4');

            // Activar botón siguiente en el Paso 1
            document.querySelectorAll('#itemSelector input[type="checkbox"]').forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const checkedBoxes = document.querySelectorAll('#itemSelector input[type="checkbox"]:checked');
                console.log("checkboxes", checkedBoxes.length)
                document.getElementById('toStep2').disabled = checkedBoxes.length === 0; // Habilitar si hay al menos uno seleccionado
            });
        });
            // Transición de pasos
            toStep2.addEventListener('click', function () {
                const selectedItems = Array.from(document.querySelectorAll('#itemSelector input[type="checkbox"]:checked')).map(
                    (checkbox) => checkbox.value
                );
                console.log("selectedItems", selectedItems)
                if (selectedItems.length === 0) {
                    alert('Por favor selecciona al menos un producto');
                    return;
                }
                fetch('api/get-variants', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ item_ids: selectedItems }),
                })
                .then((response) => {
                    // Verificar si la respuesta tiene un código de estado exitoso
                    if (!response.ok) {
                        throw new Error(`Error en la respuesta: ${response.statusText}`);
                    }
                    return response.json();
                })
                // Loop through variants
                .then((data) => {
                    const variantContainer = document.getElementById('variantContainer');
                    variantContainer.innerHTML = ''; // Limpiar contenido previo
                    variantContainer.className = 'row row-cols-1 row-cols-md-3 g-3 gap-4';

                    // Mostrar variantes agrupadas por producto
                    data.forEach((product) => {
    const matchedItem = selectedItems.find(item => item.id === product.product_id.toString());

    // Crear una tarjeta para cada producto
    const productCard = document.createElement('div');
    productCard.classList.add('card', 'mb-4', 'shadow', 'col-4', 'p-0', 'w-20', 'h-100');

    const productHeader = document.createElement('div');
    productHeader.classList.add('card-header', 'bg-success', 'text-white', 'fw-bold');
    productHeader.textContent = `Producto: ${matchedItem ? matchedItem.name : 'Desconocido'}`;
    productCard.appendChild(productHeader);

    const productBody = document.createElement('div');
    productBody.classList.add('card-body');

    // Iterar a través de las variantes del producto
    product.variants.forEach((variant) => {
        const variantRow = document.createElement('div');
        variantRow.classList.add('mb-2', 'border-bottom', 'pb-2');

        const variantLabel = document.createElement('div');
        variantLabel.textContent = `Variante: ${variant.type || 'Sin nombre'}`;
        variantLabel.classList.add('me-3', 'fw-bold');

        const sizeLabel = document.createElement('div');
        sizeLabel.textContent = `Tamaño: ${variant.size || 'Sin nombre'}`;
        sizeLabel.classList.add('me-3');

        const storageLabel = document.createElement('div');
        storageLabel.textContent = `Almacenaje: ${variant.storage_description || 'Sin nombre'}`;
        storageLabel.classList.add('me-3');

        const lifeLabel = document.createElement('div');
        lifeLabel.textContent = `Vida útil: ${variant.shelf_life_description || 'Sin nombre'}`;
        lifeLabel.classList.add('me-3', 'mb-3');

        const quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.placeholder = 'Cantidad';
        quantityInput.min = 1;
        quantityInput.id = `inputQuantity_${product.product_id}_${variant.id}`;
        quantityInput.classList.add('form-control', 'w-auto', 'border', 'border-1', 'p-2', 'bg-white', 'input-cantidad');

        quantityInput.addEventListener('input', function () {
            const quantity = parseInt(quantityInput.value) || 0; // Usar parseInt para asegurar que la cantidad sea un número

            if (quantity > 0) {
                const selectedProduct = {
                    product_id: product.product_id,
                    name: matchedItem ? matchedItem.name : 'Desconocido',
                    variant: variant, // Guardamos la variante específica
                    quantity: quantity
                };

                const existingProductIndex = itemsSelected.findIndex(
                    (item) => item.product_id === selectedProduct.product_id && item.variant.id === selectedProduct.variant.id
                );

                if (existingProductIndex > -1) {
                    // Si ya existe, actualizamos la cantidad
                    itemsSelected[existingProductIndex].quantity = quantity;
                } else {
                    // Si no existe, lo agregamos a la lista
                    itemsSelected.push(selectedProduct);
                }
            }

            console.log("itemsSelected", itemsSelected);
            document.getElementById('toStep3').disabled = itemsSelected.length === 0; // Desactivar el paso 3 si no hay selección
        });

        // Agregar la variante a la tarjeta del producto
        variantRow.appendChild(variantLabel);
        variantRow.appendChild(sizeLabel);
        variantRow.appendChild(storageLabel);
        variantRow.appendChild(lifeLabel);
        variantRow.appendChild(quantityInput);

        productBody.appendChild(variantRow);
    });

    productCard.appendChild(productBody);
    variantContainer.appendChild(productCard);
});


                    // Transición a Step 2
                    step1.classList.add('d-none');
                    step2.classList.remove('d-none');
                })

            .catch((error) => {
                console.error('Error al obtener variantes:', error);
                alert('Hubo un error al obtener las variantes. Por favor, intenta nuevamente.');
            });
            
        });
        
            toStep1.addEventListener('click', function () {
                step2.classList.add('d-none');
                step1.classList.remove('d-none');
            });
            
            toStep3.addEventListener('click', function () {
                step3.classList.remove('d-none');
                step2.classList.add('d-none');
            });

            backToStep2.addEventListener('click', function () {
                step2.classList.remove('d-none');
                step3.classList.add('d-none');
            })

            // Manejar selección de proveedores
            document.querySelectorAll('#providerSelector input[type="checkbox"]').forEach((checkbox) => {
                const providerContainer = document.getElementById('providerContainer');
                providerContainer.innerHTML = ''; // Clear previous content
                checkbox.addEventListener('change', function () {
                    const providerCheckedBoxes = document.querySelectorAll('#providerSelector input[type="checkbox"]:checked');
                    
                    // Deshabilitar/Activar botón según los proveedores seleccionados
                    document.getElementById('toStep4').disabled = providerCheckedBoxes.length === 0;

                    // Capturar IDs de proveedores seleccionados
                    const selectedProviders = Array.from(providerCheckedBoxes).map(cb => cb.value); // Suponiendo que el value del checkbox es el providerID

                    // Asociar los proveedores con los productos seleccionados
                    itemsSelected.forEach(item => {
                        item.providers = selectedProviders; // Agregar la lista de proveedores al producto
                    });

                    console.log("itemsSelected con providers:", itemsSelected);
                });
            });

            toStep4.addEventListener('click', function () {
                const providerContainer = document.getElementById('providerContainer');
                providerContainer.innerHTML = ''; // Limpiar contenido previo, si es necesario

                itemsSelected.forEach((data) => {
                    const dataRow = document.createElement('div');
                    dataRow.classList.add('d-flex', 'align-items-center', 'mb-2');

                    const productLabel = document.createElement('span');
                    productLabel.textContent = `Producto: ${data.product_id || 'Sin nombre'}`;
                    productLabel.classList.add('me-3');

                    const variantLabel = document.createElement('span');
                    variantLabel.textContent = `Variante: ${data.variant_id || 'Sin nombre'}`;
                    variantLabel.classList.add('me-3');

                    const quantityLabel = document.createElement('span');
                    quantityLabel.textContent = `Cantidad: ${data.quantity || 'Sin nombre'}`;
                    quantityLabel.classList.add('me-3');

                    if (Array.isArray(data.providers) && data.providers.length > 0) {
                        data.providers.forEach(provider => {
                            const providerLabel = document.createElement('span');
                            providerLabel.textContent = `Proveedor ID: ${provider}`;
                            providerLabel.classList.add('me-3');
                            dataRow.appendChild(providerLabel);
                        });
                    } else {
                        console.error('No se encontraron proveedores válidos para este producto.', data.providers);
                    }

                    dataRow.appendChild(productLabel);
                    dataRow.appendChild(variantLabel);
                    dataRow.appendChild(quantityLabel);

                    // Agregar la fila al contenedor de proveedores
                    providerContainer.appendChild(dataRow);
                });

                // Mostrar el paso 4 y ocultar el paso 3
                step4.classList.remove('d-none');
                step3.classList.add('d-none');
            });

            backToStep3.addEventListener('click', function () {
                step3.classList.remove('d-none');
                step4.classList.add('d-none');
            })

            // Similares transiciones para Step 2 -> Step 3 y Step 3 -> Step 4

            // Finalizar compra
            const purchaseForm = document.getElementById('purchaseForm');
            purchaseForm.addEventListener('submit', function (event) {
                event.preventDefault();
                alert('Compra confirmada');
                // Realiza la solicitud al servidor aquí
            });
            const createOrderButton = document.getElementById('createOrder');
            createOrderButton.addEventListener('click', function () {

                fetch('api/create-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ itemsSelected }),
                })
                .then((response) => {
                    // Verificar si la respuesta tiene un código de estado exitoso
                    if (!response.ok) {
                        throw new Error(`Error en la respuesta: ${response.statusText}`);
                    }
                    return response.json();
                })
                .catch((error) => {
                    console.log("err", error)
                })
            })
        });
        function filterProducts() {
            const searchInput = document.getElementById('searchInput');
            const filter = searchInput.value.toLowerCase();
            const productItems = document.querySelectorAll('.product-item');

            productItems.forEach(item => {
                const name = item.getAttribute('data-name');
                if (name.includes(filter)) {
                    item.style.display = 'block'; // Mostrar si coincide
                } else {
                    item.style.display = 'none'; // Ocultar si no coincide
                }
            });
        }

    </script>
</body>
</html>
