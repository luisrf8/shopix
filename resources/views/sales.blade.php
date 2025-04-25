<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Flujo de Compra</title>
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet">
  <style>
    .step {
        display: none;
    }
    .step:not(.d-none) {
        display: block;
    }
  </style>
</head>
<body class="g-sidenav-show  bg-gray-100" id="d-body">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 d-none d-lg-block bg-white my-2" id="sidenav-main">
    @include('layouts.navbar')
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('layouts.head')
    <!-- End Navbar -->
    <div class="mx-5 d-flex justify-content-between gap-4">
        <div class="w-75">
            <h1>Flujo de Venta</h1>
            <form id="purchaseForm">
                @csrf
                <!-- Paso 1: Selección del Ítem -->
                <div id="step1" class="step">
                    <!-- Input de Búsqueda -->
                    <div id="categoriesContainer" class="d-flex overflow-auto gap-3  py-3" style="scroll-snap-type: x mandatory;">
                        <div class="category-item flex-shrink-0" style="width: 200px; scroll-snap-align: start;" data-category="all">
                            <a href="javascript:void(0)" class="text-decoration-none category-filter">
                                <div class="card h-100">
                                    <div class="card-header mx-3 p-3 text-center">
                                        <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                            <i class="material-symbols-rounded opacity-10">all_inclusive</i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                        <h6 class="text-center mb-0 opacity-9">Todos</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @foreach($categories as $category)
                            <div class="category-item flex-shrink-0" style="width: 200px; scroll-snap-align: start;" data-category="{{ $category->id }}">
                                <a href="javascript:void(0)" class="text-decoration-none category-filter">
                                <div class="card h-100">
                                    <div class="card-header mx-3 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                        <i class="material-symbols-rounded opacity-10">category</i>
                                    </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0 opacity-9">{{ $category['name'] }}</h6>
                                    </div>
                                </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <input 
                            type="text" 
                            id="searchInput" 
                            class="form-control border border-1 p-2 bg-white" 
                            placeholder="Buscar producto..." 
                            onkeyup="filterProducts()">
                    </div>
                    <div id="itemSelector" class="row row-cols-1 row-cols-md-3 g-3">
                        @foreach($productItems as $item)
                            <div class="col product-item" data-category="{{ $item->category_id }}" data-name="{{ strtolower($item->name) }}">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex gap-4 align-items-center">
                                            <!-- Contenedor de la imagen -->
                                            <a href="{{ route('productItem', $item->id) }}" class="icon icon-shape icon-xl shadow bg-transparent text-center border border-1 border-black text-info border-radius-lg flex-shrink-0" style="width: 70px; height: 70px;">
                                                @if(isset($item->images) && count($item->images) > 0)
                                                    <img src="{{ asset('storage/' . $item->images[0]->path) }}" alt="Imagen del producto" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                                @else
                                                    <i class="material-symbols-rounded text-dark">photo_camera</i>
                                                @endif
                                            </a>
                                            <!-- Contenedor del texto -->
                                            <div class="flex-grow-1">
                                                <h5 class="text-truncate" style="max-width: calc(100% - 80px); overflow: hidden; white-space: nowrap;">{{ $item->name }}</h5>
                                                <p class="text-truncate" style="max-width: calc(100% - 80px); overflow: hidden; white-space: nowrap;">{{ $item->description }}</p>
                                            </div>
                                        </div>
                                        @foreach($item->variants as $variant)
                                            @if($variant->stock > 0)
                                            <div class="d-flex gap-5 justify-content-between align-items-center">
                                                <label for="variant_{{ $variant->id }}" class="d-block mt-2 variant-label" style="cursor: pointer;" data-product-name="{{ $item->name }}">
                                                    <input type="checkbox" class="form-check-input me-2 variant-checkbox" id="variant_{{ $variant->id }}" name="selectedVariants[]" value="{{ $variant->id }}"
                                                    data-price="{{ $variant->price }}" data-stock="{{ $variant->stock }}"
                                                    data-product-name="{{ $item->name }}"
                                                    data-size="{{ $variant->size }}">
                                                    <span>Talla: {{$variant->size}} | {{ $variant->price }} USD | Stock: {{ $variant->stock }}</span>
                                                    <i class="check-icon d-none ms-2 text-success fas fa-check"></i>
                                                </label>
                                                <i class="material-symbols-rounded text-info" style="cursor: pointer"
                                                    onclick="showProductDetails('{{ $item->name }}', '{{ $item->description }}', '{{ isset($item->images) && count($item->images) > 0 ? asset('storage/' . $item->images[0]->path) : '' }}', '{{ $variant->price }}', '{{ $variant->stock }}', '{{ $variant->size }}')">
                                                    info
                                                </i>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div id="step2" class="step d-none">
                    <h4>Paso 2: Selecciona Métodos de Pago</h4>
                    <div id="totalAmountDisplay" class="mt-3 mb-3">
                        <strong>Total a pagar: </strong><span id="totalAmountValue">0.00</span>
                    </div>
                    <div id="paymentMethods" class="mb-3">
                        <!-- Los métodos de pago se agregarán aquí dinámicamente -->
                    </div>
                    <div id="paymentSummary" class="mt-3">
                        <strong>Total ingresado: </strong><span id="totalPaid">0.00</span><br>
                        <span id="paymentMessage" class="text-danger"></span>
                    </div>
                    <button type="button" class="btn btn-secondary mt-3" id="backToStep1">Atrás</button>
                    <button type="button" class="btn btn-info mt-3" id="toStep3" disabled>Siguiente</button>
                </div>

                <div id="step3" class="step d-none">
                    <!-- Contenido del paso 3 -->
                    <h4>Paso 3: Confirmación</h4>
                    <p>Resumen de la compra y confirmación.</p>
                    <button type="button" class="btn btn-secondary mt-3" id="backToStep2">Atrás</button>
                    <button type="button" class="btn btn-success mt-3" id="confirmPurchase">Confirmar</button>
                </div>
            </form>
        </div>
        <div class="w-25 card p-4 h-100">
            <h1>Carrito</h1>
            <ul id="cartList" class="list-group"></ul>
            <div class="mt-3">
                <strong>Total:</strong> $<span id="cartTotal">0.00</span>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-dark mt-3" id="toStep2" disabled>Siguiente</button>
            </div>
        </div>
    </div>
</main>
<!-- Modal para Detalles del Producto -->
<div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productDetailModalLabel">Detalles del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-4">
                    <!-- Imagen del producto -->
                    <div style="width: 200px; height: 200px;">
                        <img id="modalProductImage" src="" alt="Imagen del producto" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                    </div>
                    <!-- Información del producto -->
                    <div>
                        <h5 id="modalProductName"></h5>
                        <p id="modalProductDescription"></p>
                        <p><strong>Precio:</strong> $<span id="modalProductPrice"></span></p>
                        <p><strong>Stock:</strong> <span id="modalProductStock"></span></p>
                        <p><strong>Talla:</strong> <span id="modalProductSize"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Core JS Files -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

<!-- Github buttons -->
<!-- <script async defer src="https://buttons.github.io/buttons.js"></script> -->
    <script>
        var selectedItems = [];
        var totalAmount = 0;
        document.addEventListener('DOMContentLoaded', function () {
            // Escuchar todos los checkboxes
            const checkboxes = document.querySelectorAll('input[name="selectedVariants[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', handleCheckboxChange);
            });
        });
        function handleCheckboxChange(e) {
            const checkbox = e.target;
            const id = checkbox.value;
            const productName = checkbox.getAttribute('data-product-name');
            const productSize = checkbox.getAttribute('data-size');
            const stock = parseInt(checkbox.getAttribute('data-stock')) || 0; // Asegúrate de que sea un número
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0; // Asegúrate de que sea un número

            if (checkbox.checked) {
                selectedItems.push({
                    id,
                    productName,
                    productSize,
                    price,
                    quantity: 1,
                    stock
                });
                totalAmount += price;
            } else {
                const removedItem = selectedItems.find(item => item.id === id);
                if (removedItem) totalAmount -= removedItem.price * removedItem.quantity;

                selectedItems = selectedItems.filter(item => item.id !== id);
            }

            renderCart();
        }

        function updateQuantity(id, newQty) {
            const item = selectedItems.find(item => item.id === id);
            if (item) {
                newQty = parseInt(newQty) || 1; // Asegúrate de que sea un número válido
                if (newQty < 1) newQty = 1;
                if (newQty > item.stock) newQty = item.stock;

                totalAmount -= item.price * item.quantity; // Quita el anterior
                item.quantity = newQty;
                totalAmount += item.price * newQty;        // Agrega el nuevo
                renderCart();
            }
        }

        function renderCart() {
            const cartList = document.getElementById('cartList');
            const cartTotal = document.getElementById('cartTotal');
            const toStep2Btn = document.getElementById('toStep2');

            cartList.innerHTML = '';

            selectedItems.forEach(item => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-start flex-column mt-2';

                const textDiv = document.createElement('div');
                textDiv.innerHTML = `<strong>${item.productName}</strong><br>
                <strong>Talla: ${item.productSize}</strong><br>
                Precio: ${item.price.toFixed(2)} USD | Stock: ${item.stock}`;

                const controlsDiv = document.createElement('div');
                controlsDiv.className = 'd-flex align-items-center justify-content-between w-100 mt-2';

                // Div para cantidad y el input con gap-2
                const quantityDiv = document.createElement('div');
                quantityDiv.className = 'd-flex align-items-center gap-2';

                const quantityLabel = document.createElement('label');
                quantityLabel.className = 'd-flex align-items-center mt-2';
                quantityLabel.textContent = 'Cantidad: ';
                // quantityLabel.className = 'me-2';

                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.min = '1';
                quantityInput.max = item.stock;
                quantityInput.value = item.quantity;
                quantityInput.className = 'form-control';
                quantityInput.style.width = '80px';
                quantityInput.style.height = 'fit-content';
                quantityInput.style.padding = '0.25rem 0.5rem';
                quantityInput.style.border = '1px solid #ced4da';
                quantityInput.oninput = () => updateQuantity(item.id, parseInt(quantityInput.value));

                quantityDiv.appendChild(quantityLabel);
                quantityDiv.appendChild(quantityInput);

                const removeBtn = document.createElement('button');
                removeBtn.className = 'btn btn-sm btn-danger mt-3';
                removeBtn.innerText = 'X';
                removeBtn.onclick = () => removeFromCart(item.id);

                controlsDiv.appendChild(quantityDiv); // Agregar el div de cantidad e input
                controlsDiv.appendChild(removeBtn);  // Botón de eliminar al extremo derecho

                li.appendChild(textDiv);
                li.appendChild(controlsDiv);
                cartList.appendChild(li);
            });

            cartTotal.textContent = totalAmount.toFixed(2); // Asegúrate de mostrar un número válido
            toStep2Btn.disabled = selectedItems.length === 0;
        }

        function removeFromCart(id) {
            const item = selectedItems.find(item => item.id === id);
            if (item) {
                totalAmount -= item.price * item.quantity;
                selectedItems = selectedItems.filter(i => i.id !== id);
            }

            const checkbox = document.getElementById(`variant_${id}`);
            if (checkbox) checkbox.checked = false;

            renderCart();
        }

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

        function showProductDetails(name, description, imageUrl, price, stock, size) {
            // Rellenar los datos del modal
            document.getElementById('modalProductName').textContent = name;
            document.getElementById('modalProductDescription').textContent = description;
            document.getElementById('modalProductPrice').textContent = price;
            document.getElementById('modalProductStock').textContent = stock;
            document.getElementById('modalProductSize').textContent = size;

            const productImage = document.getElementById('modalProductImage');
            if (imageUrl) {
                productImage.src = imageUrl;
                productImage.style.display = 'block';
            } else {
                productImage.style.display = 'none';
            }

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const categoryFilters = document.querySelectorAll('.category-filter');
            const productItems = document.querySelectorAll('.product-item');

            // Filtrar productos por categoría
            categoryFilters.forEach(filter => {
                filter.addEventListener('click', function () {
                    const selectedCategory = this.closest('.category-item').getAttribute('data-category');

                    productItems.forEach(item => {
                        const itemCategory = item.getAttribute('data-category');
                        if (selectedCategory === itemCategory || selectedCategory === 'all') {
                            item.style.display = 'block'; // Mostrar si coincide
                        } else {
                            item.style.display = 'none'; // Ocultar si no coincide
                        }
                    });

                    // Limpiar el input de búsqueda al cambiar de categoría
                    searchInput.value = '';
                });
            });

            // Filtrar productos por búsqueda
            searchInput.addEventListener('keyup', function () {
                const filter = searchInput.value.toLowerCase();

                productItems.forEach(item => {
                    const name = item.getAttribute('data-name');
                    if (name.includes(filter)) {
                        item.style.display = 'block'; // Mostrar si coincide
                    } else {
                        item.style.display = 'none'; // Ocultar si no coincide
                    }
                });
            });
        });
        toStep3.addEventListener('click', function () {
                step3.classList.remove('d-none');
                step2.classList.add('d-none');
                
                // Calcular el total de todos los productos seleccionados
                totalAmount = itemsSelected.reduce((total, item) => {
                    // Convertir el precio de la variante a número y multiplicarlo por la cantidad
                    return total + (parseFloat(item.variant.price) * item.quantity);
                }, 0);

                // Mostrar el total calculado en consola
                console.log("Total calculado:", totalAmount);
                document.getElementById('totalAmountValue').innerText = totalAmount.toFixed(2); // Formatear a dos decimales
                document.getElementById('totalAmountValueToConfirm').innerText = totalAmount.toFixed(2); // Formatear a dos decimales

                // Realizar la petición para obtener los métodos de pago con sus monedas
                fetch('/api/payment-methods', {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Datos recibidos:", data);

                    const paymentMethodsContainer = document.getElementById('paymentMethods');
                    paymentMethodsContainer.innerHTML = ''; // Limpiar el contenedor antes de agregar los nuevos métodos

                    if (Array.isArray(data)) {
                        const groupedMethods = data.reduce((acc, paymentMethod) => {
                            const currencyCode = paymentMethod.currency.code;
                            if (!acc[currencyCode]) {
                                acc[currencyCode] = [];
                            }
                            acc[currencyCode].push(paymentMethod);
                            return acc;
                        }, {});

                        for (const currencyCode in groupedMethods) {
                            const currencyMethods = groupedMethods[currencyCode];

                            const currencyTitle = document.createElement('h5');
                            currencyTitle.innerText = currencyCode;
                            paymentMethodsContainer.appendChild(currencyTitle);

                            const rowDiv = document.createElement('div');
                            rowDiv.classList.add('row', 'g-3'); 

                            currencyMethods.forEach(paymentMethod => {
                                const colDiv = document.createElement('div');
                                colDiv.classList.add('col-4');

                                const cardDiv = document.createElement('div');
                                cardDiv.classList.add('card', 'shadow-sm', 'p-3'); 

                                const cardBodyDiv = document.createElement('div');
                                cardBodyDiv.classList.add('card-body');

                                const label = document.createElement('label');
                                label.classList.add('form-check-label');
                                label.innerText = `${paymentMethod.name}`;

                                const amountInput = document.createElement('input');
                                amountInput.type = 'number';
                                amountInput.classList.add('form-control', 'mt-2', 'border', 'border-1', 'p-2');
                                amountInput.placeholder = `Monto en ${paymentMethod.currency.code}`;
                                amountInput.id = `amount${paymentMethod.id}`;

                                cardBodyDiv.appendChild(label);
                                cardBodyDiv.appendChild(amountInput);

                                cardDiv.appendChild(cardBodyDiv);
                                colDiv.appendChild(cardDiv);
                                rowDiv.appendChild(colDiv);
                            });

                            paymentMethodsContainer.appendChild(rowDiv);
                        }
                    } else {
                        console.error('La respuesta no es un arreglo válido:', data);
                    }

                    // Escuchar el cambio en los inputs de monto después de haber agregado los inputs
                    document.querySelectorAll('input[id^="amount"]').forEach(input => {
                        input.addEventListener('input', function () {
                            // Obtener el total ingresado en todos los inputs
                            let totalInput = 0;
                            
                            // Limpiar el arreglo de paymentDetails antes de agregar los nuevos detalles
                            paymentDetails = [];

                            document.querySelectorAll('input[id^="amount"]').forEach(amountInput => {
                                const paymentMethodId = amountInput.id.replace('amount', ''); // Obtener el ID del método de pago
                                const paymentAmount = parseFloat(amountInput.value) || 0; // Obtener el monto ingresado

                                // Encontrar el método de pago completo a partir del ID
                                const paymentMethod = data.find(method => method.id == paymentMethodId);

                                console.log("paymentMethodId", paymentMethodId, "paymentAmount", paymentAmount);

                                totalInput += paymentAmount;

                                // Almacenar los detalles completos del método de pago en paymentDetails
                                paymentDetails.push({
                                    id: paymentMethodId,
                                    name: paymentMethod.name,
                                    currency: paymentMethod.currency.code,
                                    amount: paymentAmount,
                                });
                            });

                            // Comparar el total ingresado con el total calculado
                            const toStep4Button = document.getElementById('toStep4');
                            if (totalInput === totalAmount) {
                                toStep4Button.disabled = false; // Habilitar el botón si el total es correcto

                                // Aquí, ahora asociamos el arreglo paymentDetails con itemsSelected
                                const updatedItemsSelected = itemsSelected.map(item => {
                                    // Para cada item, añadir la referencia al paymentDetails con el id del producto
                                    item.paymentDetails = paymentDetails.filter(payment => payment.id === item.product_id);
                                    return item;
                                });

                                // Mostrar el estado actualizado de itemsSelected y paymentDetails en la consola
                                console.log("Items seleccionados actualizados:", updatedItemsSelected);
                                console.log("Detalles de pagos:", paymentDetails);
                            } else {
                                toStep4Button.disabled = true; // Deshabilitar el botón si el total es incorrecto
                            }
                        });
                    });


                })
                .catch(error => {
                    console.error('Error al obtener los métodos de pago:', error);
                });
            });

    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1; // Contador para rastrear el paso actual
        const totalSteps = 3; // Número total de pasos

        const steps = document.querySelectorAll('.step');
        const toStep2Button = document.getElementById('toStep2');
        const toStep3Button = document.getElementById('toStep3');
        const backToStep1Button = document.getElementById('backToStep1');
        const backToStep2Button = document.getElementById('backToStep2');

        // Función para mostrar el paso actual
        function showStep(step) {
            steps.forEach((stepElement, index) => {
                if (index + 1 === step) {
                    stepElement.classList.remove('d-none');
                } else {
                    stepElement.classList.add('d-none');
                }
            });
        }

        // Avanzar al paso 2
        toStep2Button.addEventListener('click', function () {
            if (currentStep === 1) {
                currentStep = 2;
                showStep(currentStep);

                // Calcular el total de los productos seleccionados
                const totalAmount = selectedItems.reduce((total, item) => {
                    return total + item.price * item.quantity;
                }, 0);

                document.getElementById('totalAmountValue').textContent = totalAmount.toFixed(2);

                // Obtener los métodos de pago
                fetch('/api/payment-methods', {
                    method: 'GET',
                })
                    .then(response => response.json())
                    .then(data => {
                        const paymentMethodsContainer = document.getElementById('paymentMethods');
                        paymentMethodsContainer.innerHTML = ''; // Limpiar el contenedor

                        if (Array.isArray(data)) {
                            data.forEach(paymentMethod => {
                                const methodDiv = document.createElement('div');
                                methodDiv.className = 'form-check';

                                const input = document.createElement('input');
                                input.type = 'radio';
                                input.name = 'paymentMethod';
                                input.value = paymentMethod.id;
                                input.className = 'form-check-input';
                                input.id = `paymentMethod_${paymentMethod.id}`;

                                const label = document.createElement('label');
                                label.className = 'form-check-label';
                                label.htmlFor = `paymentMethod_${paymentMethod.id}`;
                                label.textContent = `${paymentMethod.name} (${paymentMethod.currency.code})`;

                                methodDiv.appendChild(input);
                                methodDiv.appendChild(label);
                                paymentMethodsContainer.appendChild(methodDiv);
                            });
                        }
                    })
                    .catch(error => console.error('Error al obtener los métodos de pago:', error));
            }
        });

        // Avanzar al paso 3
        toStep3Button.addEventListener('click', function () {
            if (currentStep === 2) {
                currentStep = 3;
                showStep(currentStep);
            }
        });

        // Regresar al paso 1
        backToStep1Button.addEventListener('click', function () {
            if (currentStep === 2) {
                currentStep = 1;
                showStep(currentStep);
            }
        });

        // Regresar al paso 2
        backToStep2Button.addEventListener('click', function () {
            if (currentStep === 3) {
                currentStep = 2;
                showStep(currentStep);
            }
        });

        // Mostrar el paso inicial
        showStep(currentStep);
    });
    document.addEventListener('DOMContentLoaded', function () {
    const toStep2Button = document.getElementById('toStep2');
    const toStep3Button = document.getElementById('toStep3');
    const backToStep1Button = document.getElementById('backToStep1');
    const paymentMethodsContainer = document.getElementById('paymentMethods');
    let totalAmount = 0;
    let paymentDetails = [];

    // Función para mostrar los métodos de pago separados por monedas
    function loadPaymentMethods() {
        fetch('/api/payment-methods', {
            method: 'GET',
        })
            .then(response => response.json())
            .then(data => {
                paymentMethodsContainer.innerHTML = ''; // Limpiar el contenedor

                if (Array.isArray(data)) {
                    const groupedMethods = data.reduce((acc, method) => {
                        const currency = method.currency.code;
                        if (!acc[currency]) acc[currency] = [];
                        acc[currency].push(method);
                        return acc;
                    }, {});

                    for (const currency in groupedMethods) {
                        const currencyGroup = groupedMethods[currency];

                        // Título de la moneda
                        const currencyTitle = document.createElement('h5');
                        currencyTitle.textContent = `Métodos de Pago en ${currency}`;
                        paymentMethodsContainer.appendChild(currencyTitle);

                        // Contenedor de los métodos de pago
                        const methodGroupDiv = document.createElement('div');
                        methodGroupDiv.className = 'mb-3';

                        currencyGroup.forEach(method => {
                            const methodDiv = document.createElement('div');
                            methodDiv.className = 'form-check mb-2';

                            const input = document.createElement('input');
                            input.type = 'checkbox';
                            input.className = 'form-check-input payment-method-checkbox';
                            input.id = `paymentMethod_${method.id}`;
                            input.dataset.methodId = method.id;
                            input.dataset.currency = currency;

                            const label = document.createElement('label');
                            label.className = 'form-check-label';
                            label.htmlFor = `paymentMethod_${method.id}`;
                            label.textContent = method.name;

                            // Input para el monto (oculto inicialmente)
                            const amountInput = document.createElement('input');
                            amountInput.type = 'number';
                            amountInput.className = 'form-control mt-2 d-none payment-amount-input';
                            amountInput.placeholder = `Monto en ${currency}`;
                            amountInput.dataset.methodId = method.id;

                            // Mostrar el input al seleccionar el método
                            input.addEventListener('change', function () {
                                if (this.checked) {
                                    amountInput.classList.remove('d-none');
                                    paymentDetails.push({
                                        id: method.id,
                                        name: method.name,
                                        currency: currency,
                                        amount: 0,
                                    });
                                } else {
                                    amountInput.classList.add('d-none');
                                    paymentDetails = paymentDetails.filter(detail => detail.id !== method.id);
                                }
                                validatePaymentDetails();
                            });

                            // Actualizar el monto ingresado
                            amountInput.addEventListener('input', function () {
                                const methodId = parseInt(this.dataset.methodId);
                                const detail = paymentDetails.find(detail => detail.id === methodId);
                                if (detail) {
                                    detail.amount = parseFloat(this.value) || 0;
                                }
                                validatePaymentDetails();
                            });

                            methodDiv.appendChild(input);
                            methodDiv.appendChild(label);
                            methodDiv.appendChild(amountInput);
                            methodGroupDiv.appendChild(methodDiv);
                        });

                        paymentMethodsContainer.appendChild(methodGroupDiv);
                    }
                }
            })
            .catch(error => console.error('Error al cargar los métodos de pago:', error));
    }

    // Validar los detalles de pago
    function validatePaymentDetails() {
        const totalInput = paymentDetails.reduce((sum, detail) => sum + detail.amount, 0);
        toStep3Button.disabled = totalInput !== totalAmount;
    }

    // Avanzar al paso 2
    toStep2Button.addEventListener('click', function () {
        totalAmount = selectedItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
        document.getElementById('totalAmountValue').textContent = totalAmount.toFixed(2);
        loadPaymentMethods();
    });

    // Avanzar al paso 3
    toStep3Button.addEventListener('click', function () {
        console.log('Detalles de pago:', paymentDetails);
        // Aquí puedes enviar los detalles de pago al servidor o continuar con el flujo
    });

    // Regresar al paso 1
    backToStep1Button.addEventListener('click', function () {
        paymentDetails = []; // Reiniciar los detalles de pago
    });
});
    document.addEventListener('DOMContentLoaded', function () {
    const toStep2Button = document.getElementById('toStep2');
    const toStep3Button = document.getElementById('toStep3');
    const backToStep1Button = document.getElementById('backToStep1');
    const paymentMethodsContainer = document.getElementById('paymentMethods');
    const totalPaidElement = document.getElementById('totalPaid');
    const paymentMessageElement = document.getElementById('paymentMessage');
    let totalAmount = 0;
    let paymentDetails = [];

    // Función para mostrar los métodos de pago separados por monedas
    function loadPaymentMethods() {
        fetch('/api/payment-methods', {
            method: 'GET',
        })
            .then(response => response.json())
            .then(data => {
                paymentMethodsContainer.innerHTML = ''; // Limpiar el contenedor

                if (Array.isArray(data)) {
                    const groupedMethods = data.reduce((acc, method) => {
                        const currency = method.currency.code;
                        if (!acc[currency]) acc[currency] = [];
                        acc[currency].push(method);
                        return acc;
                    }, {});

                    for (const currency in groupedMethods) {
                        const currencyGroup = groupedMethods[currency];

                        // Título de la moneda
                        const currencyTitle = document.createElement('h5');
                        currencyTitle.textContent = `Métodos de Pago en ${currency}`;
                        paymentMethodsContainer.appendChild(currencyTitle);

                        // Contenedor de los métodos de pago
                        const methodGroupDiv = document.createElement('div');
                        methodGroupDiv.className = 'mb-3';

                        currencyGroup.forEach(method => {
                            const methodDiv = document.createElement('div');
                            methodDiv.className = 'form-check mb-2';

                            const input = document.createElement('input');
                            input.type = 'checkbox';
                            input.className = 'form-check-input payment-method-checkbox';
                            input.id = `paymentMethod_${method.id}`;
                            input.dataset.methodId = method.id;
                            input.dataset.currency = currency;

                            const label = document.createElement('label');
                            label.className = 'form-check-label';
                            label.htmlFor = `paymentMethod_${method.id}`;
                            label.textContent = method.name;

                            // Input para el monto (oculto inicialmente)
                            const amountInput = document.createElement('input');
                            amountInput.type = 'number';
                            amountInput.className = 'form-control mt-2 d-none payment-amount-input';
                            amountInput.placeholder = `Monto en ${currency}`;
                            amountInput.dataset.methodId = method.id;

                            // Mostrar el input al seleccionar el método
                            input.addEventListener('change', function () {
                                if (this.checked) {
                                    amountInput.classList.remove('d-none');
                                    paymentDetails.push({
                                        id: method.id,
                                        name: method.name,
                                        currency: currency,
                                        amount: 0,
                                    });
                                } else {
                                    amountInput.classList.add('d-none');
                                    paymentDetails = paymentDetails.filter(detail => detail.id !== method.id);
                                }
                                validatePaymentDetails();
                            });

                            // Actualizar el monto ingresado
                            amountInput.addEventListener('input', function () {
                                const methodId = parseInt(this.dataset.methodId);
                                const detail = paymentDetails.find(detail => detail.id === methodId);
                                if (detail) {
                                    detail.amount = parseFloat(this.value) || 0;
                                }
                                validatePaymentDetails();
                            });

                            methodDiv.appendChild(input);
                            methodDiv.appendChild(label);
                            methodDiv.appendChild(amountInput);
                            methodGroupDiv.appendChild(methodDiv);
                        });

                        paymentMethodsContainer.appendChild(methodGroupDiv);
                    }
                }
            })
            .catch(error => console.error('Error al cargar los métodos de pago:', error));
    }

    // Validar los detalles de pago
    function validatePaymentDetails() {
        const totalInput = paymentDetails.reduce((sum, detail) => sum + detail.amount, 0);
        totalPaidElement.textContent = totalInput.toFixed(2);

        if (totalInput > totalAmount) {
            const change = totalInput - totalAmount;
            paymentMessageElement.textContent = `Vuelto: $${change.toFixed(2)}`;
            paymentMessageElement.classList.remove('text-danger');
            paymentMessageElement.classList.add('text-success');
            toStep3Button.disabled = false;
        } else if (totalInput < totalAmount) {
            const remaining = totalAmount - totalInput;
            paymentMessageElement.textContent = `Falta: $${remaining.toFixed(2)}`;
            paymentMessageElement.classList.remove('text-success');
            paymentMessageElement.classList.add('text-danger');
            toStep3Button.disabled = true;
        } else {
            paymentMessageElement.textContent = '';
            toStep3Button.disabled = false;
        }
    }

    // Avanzar al paso 2
    toStep2Button.addEventListener('click', function () {
        totalAmount = selectedItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
        document.getElementById('totalAmountValue').textContent = totalAmount.toFixed(2);
        loadPaymentMethods();
    });

    // Avanzar al paso 3
    toStep3Button.addEventListener('click', function () {
        console.log('Detalles de pago:', paymentDetails);
        // Aquí puedes enviar los detalles de pago al servidor o continuar con el flujo
    });

    // Regresar al paso 1
    backToStep1Button.addEventListener('click', function () {
        paymentDetails = []; // Reiniciar los detalles de pago
    });
});
    </script>
</body>
</html>
