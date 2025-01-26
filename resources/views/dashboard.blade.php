<!--
=========================================================
* Material Dashboard 3 - v3.2.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    El Hombre Casual
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
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
        <div class="ms-3">
          <h3 class="mb-0 h4 font-weight-bolder">El Hombre Casual</h3>
          <p class="mb-4">
            Datos y Análisis.
          </p>
        </div>
        @foreach($stats as $stat)
        <a href="{{ $stat['link'] }}" class="text-decoration-none col-xl-3 col-sm-6">
            <div class="card">
              <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                  <div>
                    <p class="text-sm mb-0 text-capitalize">{{$stat['name']}}</p>
                    <h4 class="mb-0">{{$stat['count']}}</h4>
                  </div>
                  <div class="icon icon-md icon-shape bg-gradient-info shadow-dark shadow text-center border-radius-lg">
                    <i class="material-symbols-rounded opacity-10">leaderboard</i>
                  </div>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-2 ps-3">
              </div>
            </div>
        </a>
        @endforeach
      </div>

      <div class="pt-4">
        <div class="pb-0 px-3 d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <h6 class="mb-0">Ventas Realizadas</h6>
            <a href="/sales-orders" class="mx-4 text-info">Ver Más ></a>
          </div>
          <a class="btn bg-gradient-info mb-0 mx-3" href="/sales">
            <i class="material-symbols-rounded text-sm">add</i>&nbsp;&nbsp;Realizar Venta
          </a>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="row">
              <div class="container-fluid py-4 row">
                @foreach($salesOrders as $order)
                <div class="col-md-4 col-4">
                  <a href="" class="text-decoration-none">
                    <div class="card">
                      <div class="card-header mx-4 p-3 text-center">
                        <h6 class="text-center mb-0 opacity-9">Orden de Compra Nro {{ $order->id }}</h6>
                      </div>
                      <div class="card-body pt-0 p-3 text-center">
                        <div class="mt-2">
                          <!-- <h6 class="text-left opacity-8">Inventario</h6> -->
                          <div class="d-flex justify-content-between align-items-center px-3 py-2 border rounded bg-lighter">
                              <div class="text-start column">
                                  <div class="text-xs text-bold mb-2">Total de Productos:</div>
                                  <div class="text-xs text-bold mb-2">Usuario:</div>
                              </div>
                              <div class="text-end column">
                                  <div class="text-xs mb-2">{{ $order->total_items }}</div>
                                  <div class="text-xs mb-2">
                                      @if ($order->user)
                                          {{ $order->user->name }}
                                      @else
                                          Usuario no asignado
                                      @endif
                                  </div>
                              </div>
                          </div>
                      </div>
                      </div>
                      <!-- Acciones -->
                      <div class="mt-3 d-flex justify-content-center mx-4 ">
                          <a class="btn btn-sm btn-outline-info btn-edit-provider" href="/sales/{{ $order->id }}">Ver Detalles</a>
                      </div>
                    </div>
                  </a>
                </div>
                @endforeach
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="">
        <div class="pb-0 px-3 d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <h6 class="mb-0">Compras Realizadas</h6>
            <a href="/purchase-orders" class="mx-4 text-info">Ver Más ></a>
          </div>
          <a class="btn bg-gradient-info mb-0 mx-3" href="/purchase">
            <i class="material-symbols-rounded text-sm">add</i>&nbsp;&nbsp;Realizar Compra
          </a>
        </div>
        <div class="row">
            <div class="container-fluid py-4 row">
              @foreach($purchaseOrders as $order)
              <div class="col-md-4 col-4">
                <a href="" class="text-decoration-none">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      <h6 class="text-center mb-0 opacity-9">Orden de Compra Nro {{ $order->id }}</h6>
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <div class="mt-2">
                        <!-- <h6 class="text-left opacity-8">Inventario</h6> -->
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border rounded bg-lighter">
                            <div class="text-start column">
                                <div class="text-xs text-bold mb-2">Proveedor:</div>
                                <div class="text-xs text-bold mb-2">Cantidad de productos:</div>
                                <div class="text-xs text-bold">Fecha de Creación:</div>
                            </div>
                            <div class="text-end column">
                                <div class="text-xs mb-2">{{ $order->provider_id }}</div>
                                <div class="text-xs mb-2">{{ $order->total_items }}</div>
                                <div class="text-xs">{{ $order->date }}</div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- Acciones -->
                    <div class="mt-3 d-flex justify-content-center mx-4 ">
                        <!-- <button class="btn btn-sm btn-outline-info">Editar</button> -->
                        <a class="btn btn-sm btn-outline-info btn-edit-provider" href="/order/{{ $order->id }}">Ver Detalles</a>
                    </div>
                  </div>
                </a>
              </div>
              @endforeach
            </div>

          </div>
      </div>

    </div>
      
      </div>
      <div class="row mb-4">
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
        </div>
        <div class="col-lg-4 col-md-6">
        </div>
      </div>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script>

  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
</body>

</html>