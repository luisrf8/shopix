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
            <span id="customerId" data-rate="{{ $customerId}}"></span>
            <form id="purchaseForm">
                @csrf
                <!-- Paso 1: Selección del Ítem -->
                <div id="step1" class="step">
                    <!-- Input de Búsqueda -->
                    <div class="">
                        <input 
                            type="text" 
                            id="searchCategory" 
                            class="form-control border border-1 p-2 bg-white" 
                            placeholder="Buscar categoría..." 
                            onkeyup="filterCategories()">
                    </div>
                    <div id="categoriesContainer" class="d-flex overflow-auto gap-3 py-3 mb-2" style="scroll-snap-type: x mandatory;">
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
                            <div class="category-item flex-shrink-0" style="width: 200px; scroll-snap-align: start;" data-category-name="{{ $category->name }}" data-category="{{ $category->id }}">
                                <a href="javascript:void(0)" class="text-decoration-none category-filter">
                                <div class="card h-100">
                                    <div class="card-header mx-3 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                        <i class="material-symbols-rounded opacity-10">category</i>
                                    </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0 opacity-9">{{ $category->name }}</h6>
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
                    <div id="totalAmountDisplay" class="mt-3">
                        <strong>Total a pagar: </strong><span id="totalAmountValue">0.00</span>
                    </div>
                    <div class="mb-3">
                        <strong>Tasa BCV: </strong><span id="dollarRate" data-rate="{{ number_format($dollarRate->rate, 2, '.', '') }}">{{ number_format($dollarRate->rate, 2) }} Bs.</span>
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
    <script>
        var selectedItems = [];
        var totalAmount = 0;
        const dollarRate = parseFloat(document.getElementById('dollarRate').dataset.rate);
        const customerId = document.getElementById('customerId').dataset.rate; // Asegúrate de que esta variable esté definida correctamente
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

        function filterCategories() {
            const searchValue = document.getElementById('searchCategory').value.toLowerCase();
            const categories = document.querySelectorAll('.category-item');

            categories.forEach(category => {
                const name = category.getAttribute('data-category-name')?.toLowerCase() || '';
                if (name.includes(searchValue) || category.getAttribute('data-category') === 'all') {
                    category.style.display = 'block';
                } else {
                    category.style.display = 'none';
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function () {
        const toStep2Button = document.getElementById('toStep2');
        const toStep3Button = document.getElementById('toStep3');
        const backToStep1Button = document.getElementById('backToStep1');
        const paymentMethodsContainer = document.getElementById('paymentMethods');
        let totalAmount = 0;
        let paymentDetails = [];

        // Función para mostrar los métodos de pago separados por monedas
        function loadPaymentMethods() {
            toStep2Button.classList.remove('d-block'); // Ocultar el botón de siguiente
            toStep2Button.classList.add('d-none'); // Ocultar el botón de siguiente
            fetch('/api/payment-methods', {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    console.log("Métodos de pago:", data);
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
                        methodGroupDiv.className = 'mb-3 card d-flex flex-row align-items-center gap-2 p-4 border border-radius-lg';
                        methodGroupDiv.style.width = 'fit-content';
                        methodGroupDiv.style.height = 'fit-content';

                        currencyGroup.forEach(method => {
                            const methodDiv = document.createElement('div');
                            methodDiv.className = 'card p-3 mb-3 border border-radius-lg col-12 col-md-4 d-flex flex-column align-items-center gap-3';
                            methodDiv.style.width = '21rem';
                            methodDiv.style.height = '16rem';

                            // Contenedor para el QR y los datos
                            const qrAndInfoDiv = document.createElement('div');
                            qrAndInfoDiv.className = 'd-flex align-items-center gap-3';

                            // QR Code
                            if (method.qr_image) {
                                const qrImage = document.createElement('img');
                                qrImage.src = `/storage/${JSON.parse(method.qr_image)[0]}`;
                                qrImage.alt = 'QR Code';
                                qrImage.style.width = '100px';
                                qrImage.style.height = '100px';
                                qrImage.style.objectFit = 'cover';
                                qrImage.style.borderRadius = '8px';
                                qrAndInfoDiv.appendChild(qrImage);
                            }

                            // Información adicional del método de pago
                            const additionalInfo = document.createElement('div');
                            additionalInfo.className = 'text-sm';

                            if (method.dni) {
                                const dniInfo = document.createElement('label');
                                dniInfo.textContent = `ID: ${method.dni}`;
                                additionalInfo.appendChild(dniInfo);
                            }

                            if (method.bank) {
                                const bankInfo = document.createElement('label');
                                bankInfo.textContent = `Banco: ${method.bank}`;
                                additionalInfo.appendChild(bankInfo);
                            }

                            if (method.admin_name) {
                                const adminName = document.createElement('label');
                                adminName.textContent = `Nombre del Beneficiario: ${method.admin_name}`;
                                additionalInfo.appendChild(adminName);
                            }


                            // Checkbox para seleccionar el método de pago
                            const input = document.createElement('input');
                            input.type = 'checkbox';
                            input.className = 'form-check-input';
                            input.id = `paymentMethod_${method.id}`;
                            input.dataset.methodId = method.id;
                            input.dataset.currency = currency;

                            const label = document.createElement('label');
                            label.className = 'form-check-label';
                            label.htmlFor = `paymentMethod_${method.id}`;
                            label.textContent = method.name;

                            const checkboxContainer = document.createElement('div');
                            checkboxContainer.className = 'd-flex w-100 align-items-center';
                            checkboxContainer.appendChild(input);
                            checkboxContainer.appendChild(label);

                            additionalInfo.appendChild(checkboxContainer);
                            qrAndInfoDiv.appendChild(additionalInfo);
                            methodDiv.appendChild(qrAndInfoDiv);

                            // Input para el monto (oculto inicialmente)
                            const amountInput = document.createElement('input');
                            amountInput.type = 'number';
                            amountInput.className = 'form-control d-none border border-radius-lg mx-4 p-2';
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
                                    console.log("Detalles de pago:", paymentDetails);
                                } else {
                                    amountInput.classList.add('d-none');
                                    paymentDetails = paymentDetails.filter(detail => detail.id !== method.id);
                                }
                                validatePaymentDetails();
                            });

                            // Actualizar el monto ingresado
                            amountInput.addEventListener('input', function () {
                                const rawAmount = parseFloat(this.value) || 0;
                                let convertedAmount = rawAmount;

                                if (currency === 'BS') {
                                    convertedAmount = rawAmount / dollarRate;
                                }

                                // Encuentra y actualiza el paymentDetail correspondiente
                                const detail = paymentDetails.find(p => p.id === method.id);
                                if (detail) {
                                    detail.amount = convertedAmount;
                                }

                                validatePaymentDetails(); // Para actualizar resumen y validaciones
                            });

                            methodDiv.appendChild(amountInput);
                            methodGroupDiv.appendChild(methodDiv);
                        });

                        paymentMethodsContainer.appendChild(methodGroupDiv);
                    }
                }
                    document.getElementById('step2').classList.remove('d-none');
                    document.getElementById('step1').classList.add('d-none');
                })
                .catch(error => console.error('Error al cargar los métodos de pago:', error));
            }

            // Validar los detalles de pago
            function validatePaymentDetails() {
                const totalPaid = paymentDetails.reduce((sum, detail) => sum + detail.amount, 0);
                const totalPaidSpan = document.getElementById('totalPaid');
                const paymentMessage = document.getElementById('paymentMessage');

                // Mostrar el total ingresado
                totalPaidSpan.textContent = totalPaid.toFixed(2);

                // Mostrar mensaje correspondiente
                if (totalPaid < totalAmount) {
                    const remaining = (totalAmount - totalPaid).toFixed(2);
                    paymentMessage.textContent = `Falta por pagar: $${remaining}`;
                    paymentMessage.className = 'text-danger';
                } else if (totalPaid > totalAmount) {
                    const change = (totalPaid - totalAmount).toFixed(2);
                    paymentMessage.textContent = `Debe entregar vuelto: $${change}`;
                    paymentMessage.className = 'text-warning';
                } else {
                    paymentMessage.textContent = `Pago exacto.`;
                    paymentMessage.className = 'text-success';
                }

                // Habilitar o deshabilitar el botón
                toStep3Button.disabled = totalPaid < totalAmount;
            }


            // Avanzar al paso 2
            toStep2Button.addEventListener('click', function () {
                totalAmount = selectedItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
                document.getElementById('totalAmountValue').textContent = totalAmount.toFixed(2);
                loadPaymentMethods();
            });

            toStep3Button.addEventListener('click', function () {
                console.log('Detalles de pago:', paymentDetails);
                console.log('Detalles de selectedItems:', selectedItems);
                console.log('Detalles de customerId:', customerId);

                const formData = new FormData();

                // Enviar customerId y totalAmount
                formData.append('customer_id', customerId); // Define esta variable antes
                formData.append('totalAmount', totalAmount);

                // Agregar itemsSelected como JSON string
                formData.append('itemsSelected', JSON.stringify(selectedItems));

                // Agregar paymentDetails como objetos separados
                paymentDetails.forEach((payment, index) => {
                    formData.append(`paymentDetails[${index}][id]`, payment.id);
                    formData.append(`paymentDetails[${index}][name]`, payment.name);
                    formData.append(`paymentDetails[${index}][currency]`, payment.currency);
                    formData.append(`paymentDetails[${index}][amount]`, payment.amount);

                    // Si tiene imagen (opcional)
                    if (payment.image) {
                        formData.append(`paymentDetails[${index}][image]`, payment.image);
                    }
                });

                fetch('/api/create-sale', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                    } else {
                        alert('Venta registrada exitosamente.');
                        // Redirigir o limpiar formularios si es necesario
                    }
                })
                .catch(error => {
                    console.error('Error al enviar datos:', error);
                    alert('Ocurrió un error en la solicitud.');
                });
            });


            // Regresar al paso 1
            backToStep1Button.addEventListener('click', function () {
            toStep2Button.classList.remove('d-none'); // Ocultar el botón de siguiente
            toStep2Button.classList.add('d-block'); // Ocultar el botón de siguiente
            document.getElementById('step2').classList.remove('d-none');
            document.getElementById('step1').classList.add('d-none');
                paymentDetails = []; // Reiniciar los detalles de pago
            });
        });
    </script>
</body>
</html>
