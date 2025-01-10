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
  <script src="https://kit.fontawesome.com/842bd4ebad.js" crossorigin="anonymous"></script>
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
        <h1>Flujo de Venta</h1>
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
                <button type="button" class="btn btn-info mt-3" id="toStep2" disabled>Siguiente</button>
            </div>
            <!-- Paso 2: Selección de Variante y Cantidad -->
            <div id="step2" class="d-none">
                <h4>Paso 2: Selecciona Variante y Cantidad</h4>
                <div id="variantContainer"></div>
                <button type="button" class="btn btn-secondary mt-3" id="toStep1">Atrás</button>
                <button type="button" class="btn btn-info mt-3" id="toStep3" disabled>Siguiente</button>
            </div>

            <!-- Paso 3: Selección de Métodos de Pago -->
            <div id="step3" class="d-none">
                <h4>Paso 3: Selecciona Métodos de Pago</h4>
                <div id="totalAmountDisplay" class="mt-3 mb-3">
                    <strong>Total a pagar: </strong><span id="totalAmountValue">0.00</span>
                </div>
                <!-- Lista de métodos de pago -->
                <div id="paymentMethods" class="mb-3">
                    <!-- Los métodos de pago se agregarán aquí dinámicamente -->
                </div>
                <button type="button" class="btn btn-secondary mt-3" id="backToStep2">Atrás</button>
                <button type="button" class="btn btn-info mt-3" id="toStep4" disabled>Siguiente</button>
            </div>

            <!-- Paso 4: Confirmación -->
            <div id="step4" class="d-none">
                <h4>Paso 4: Confirmación</h4>
                <div id="providerContainer"></div>
                <div id="paymentMethodsSelected" class="mt-3 mb-3">
                </div>
                <div id="totalAmountToConfirm" class="mt-3 mb-3">
                    <strong>Total a pagar: </strong><span id="totalAmountValueToConfirm">0.00</span> USD
                </div>
                <button type="button" class="btn btn-secondary mt-3" id="backToStep3">Atrás</button>
                <button type="button" class="btn btn-info mt-3" id="createOrder">Continuar</button>
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
        document.addEventListener('DOMContentLoaded', function () {
            var itemsSelected= [];
            var paymentDetails= [];
            var totalAmount = 0;
            const authenticatedUserId = {{ auth()->user()->id }};
            console.log("authenticatedUserId", authenticatedUserId)
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
            const providerInput = document.getElementById("providerName");

            // Activar botón siguiente en el Paso 1
            document.querySelectorAll('#itemSelector input[type="checkbox"]').forEach((checkbox) => {
                checkbox.addEventListener('change', function () {
                    const checkedBoxes = document.querySelectorAll('#itemSelector input[type="checkbox"]:checked');
                    console.log("hola")
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
                fetch('api/sales/get-variants', {
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
                        // Crear una tarjeta para cada producto
                        const productCard = document.createElement('div');
                        productCard.classList.add('card', 'mb-4', 'shadow', 'col-4', 'p-0', 'w-20', 'h-100');

                        const productHeader = document.createElement('div');
                        productHeader.classList.add('card-header', 'bg-info', 'text-white', 'fw-bold');
                        productHeader.textContent = `Producto: ${product.product_name}`;
                        productCard.appendChild(productHeader);

                        const productBody = document.createElement('div');
                        productBody.classList.add('card-body');

                        // Iterar a través de las variantes del producto
                        product.variants.forEach((variant) => {
                            const variantRow = document.createElement('div');
                            variantRow.classList.add('mb-2', 'border-bottom', 'pb-2');

                            const variantLabel = document.createElement('div');
                            variantLabel.textContent = `Talla: ${variant.size || 'Sin nombre'}`;
                            variantLabel.classList.add('me-3', 'fw-bold');

                            const sizeLabel = document.createElement('div');
                            sizeLabel.textContent = `Stock: ${variant.stock || 'Sin nombre'}`;
                            sizeLabel.classList.add('me-3');

                            const priceLabel = document.createElement('div');
                            priceLabel.textContent = `Precio: ${variant.price || 'Sin nombre'} $`;
                            priceLabel.classList.add('me-3');

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
                                        name: product.product_name,
                                        variant: variant, // Guardamos la variante específica
                                        quantity: quantity,
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
                            variantRow.appendChild(priceLabel);
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


            backToStep2.addEventListener('click', function () {
                step2.classList.remove('d-none');
                step3.classList.add('d-none');
            })

            toStep4.addEventListener('click', function () {
                const providerContainer = document.getElementById('providerContainer');
                providerContainer.innerHTML = ''; // Limpiar contenido previo, si es necesario

                itemsSelected.forEach((data) => {
                    const dataRow = document.createElement('div');
                    dataRow.classList.add('d-flex', 'align-items-center', 'mb-2');

                    const productLabel = document.createElement('span');
                    productLabel.textContent = `Producto: ${data.name || 'Sin nombre'}`;
                    productLabel.classList.add('me-3');

                    const variantLabel = document.createElement('span');
                    variantLabel.textContent = `Talla: ${data.variant.size || 'Sin nombre'}`;
                    variantLabel.classList.add('me-3');

                    const quantityLabel = document.createElement('span');
                    quantityLabel.textContent = `Cantidad: ${data.quantity || 'Sin nombre'}`;
                    quantityLabel.classList.add('me-3');

                    const priceLabel = document.createElement('span');
                    priceLabel.textContent = `Precio: ${data.variant.price || 'Sin nombre'} USD`;
                    priceLabel.classList.add('me-3');


                    dataRow.appendChild(productLabel);
                    dataRow.appendChild(variantLabel);
                    dataRow.appendChild(quantityLabel);
                    dataRow.appendChild(priceLabel);

                    // Agregar la fila al contenedor de proveedores
                    providerContainer.appendChild(dataRow);
                });
                // Mostrar los métodos de pago seleccionados
                const paymentMethodsSelectedContainer = document.getElementById('paymentMethodsSelected');
                paymentMethodsSelectedContainer.innerHTML = ''; // Limpiar contenido previo

                // Crear una etiqueta para "Métodos de Pago"
                const paymentTitle = document.createElement('h5');
                paymentTitle.textContent = 'Métodos de Pago Seleccionados:';
                paymentMethodsSelectedContainer.appendChild(paymentTitle);

                // Mostrar cada método de pago con su nombre, moneda y monto
                paymentDetails.forEach((payment) => {
                    const paymentRow = document.createElement('div');
                    paymentRow.classList.add('mb-2');

                    const paymentText = document.createElement('span');
                    paymentText.textContent = `${payment.name || 'Sin nombre'} - ${payment.amount || '0.00'} ${payment.currency || 'Sin moneda'}`;
                    paymentRow.appendChild(paymentText);

                    // Agregar la fila de pago al contenedor de métodos de pago seleccionados
                    paymentMethodsSelectedContainer.appendChild(paymentRow);
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
                const orderData = {
                    itemsSelected: itemsSelected,
                    paymentDetails: paymentDetails,
                    totalAmount: totalAmount,
                    customer_id: authenticatedUserId
                };

                fetch('api/create-sale', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(orderData),
                })
                .then((response) => {
                    if (response.status === 200) { // Valida el código de estado HTTP
                        alert('Compra creada correctamente');
                        window.location.href = '/products';
                    }
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
