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
    Infinity Center
  </title>
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      <div class="row">
        <div class="ms-3">
          <h3 class="mb-0 h4 font-weight-bolder">Infinity Center</h3>
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
                  <div class="icon icon-md icon-shape bg-gray-900 shadow-dark shadow text-center border-radius-lg">
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
      <div class="row mt-4">
                <div class="col-lg-4 col-md-6 mt-4 mb-4">
                    <div class="card z-index-2 ">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                                <div class="chart">
                                    <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-0 ">Ventas Diarias</h6>
                            <p class="text-sm ">Ventas de la ultima semana.</p>
                            <hr class="dark horizontal">

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-4 mb-4">
                    <div class="card z-index-2  ">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                                <div class="chart">
                                    <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-0 ">Ventas Mensuales</h6>
                            <p class="text-sm ">Ventas de los ultimos meses.</p>
                            <hr class="dark horizontal">

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mb-3">
                    <div class="card z-index-2">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                                <div class="chart">
                                    <canvas id="chart-line-tasks" class="chart-canvas" height="170"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-0">Productos mas vendidos</h6>
                            <p class="text-sm">Análisis general.</p>
                            <hr class="horizontal">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0">
                          <div class="pb-0 px-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                              <h6 class="mb-0">Ventas Realizadas</h6>
                              <a href="/sales-orders" class="mx-4 text-black">Ver Más ></a>
                            </div>
                            <a class="" href="/sales">
                              <i class="material-symbols-rounded text-sm">add</i>&nbsp;&nbsp;Realizar Venta
                            </a>
                          </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Orden</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Usuario</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesOrders as $order)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="avatar avatar-sm me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Orden #{{ $order->id }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-xs font-weight-bold">
                                        @if ($order->user)
                                            {{ $order->user->name }}
                                        @else
                                            Usuario no asignado
                                        @endif
                                    </span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">{{ $order->date }}</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">${{ number_format($order->details->sum('price'), 2) }}</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">
                                        @if ($order->status == 0)
                                            <span class="badge bg-warning">En Proceso</span>
                                        @elseif ($order->status == 1)
                                            <span class="badge bg-success">Aprobado</span>
                                        @elseif ($order->status == 2)
                                            <span class="badge bg-danger">Negado</span>
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                          <div class="pb-0 px-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                              <h6 class="mb-0">Compras Realizadas</h6>
                              <a href="/purchase-orders" class="mx-4 btn-outline-black">Ver Más ></a>
                            </div>
                            <a class="" href="/purchase">
                              <i class="material-symbols-rounded text-sm">add</i>&nbsp;&nbsp;Realizar Compra
                            </a>
                          </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side" id="timeline">
                                @foreach($purchaseOrders as $order)
                                <div class="timeline-block mb-1">
                                    <span class="timeline-step">
                                      <i class="material-symbols-rounded text-success opacity-10" style="font-size: 30px">add_shopping_cart</i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">
                                            Orden de Compra #{{ $order->id }}
                                        </h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            Proveedor: {{ $order->provider_id ?? 'No asignado' }} 
                                        </p>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            Fecha: {{ $order->date }}
                                        </p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        // Asegúrate de que las variables estén bien formateadas para JS
        const weeklySalesCount = @json($weeklySalesCount); // Por ejemplo: [50, 20, 10, 22, 50, 10, 40]
        const monthlySalesFormatted = @json($monthlySalesFormatted); // Podría ser un número o string
        const months = @json($months); // Por ejemplo: [50, 20, 10, 22, 50, 10, 40]
        const topProductNames = @json($topProductNames); // ["Producto A", "Producto B", ...]
        const topProductSales = @json($topProductSales); // [120, 90, 70, 50, 30]
        var ctx = document.getElementById("chart-bars").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["L", "M", "M", "J", "V", "S", "D"],
                datasets: [{
                    label: "Ventas semanales",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "rgba(255, 255, 255, .8)",
                    data: weeklySalesCount,
                    maxBarThickness: 6
                }, ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#fff"
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });


        var ctx2 = document.getElementById("chart-line").getContext("2d");

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: months,
                datasets: [{
                    label: "Ventas mensuales",
                    tension: 0,
                    borderWidth: 0,
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(255, 255, 255, .8)",
                    pointBorderColor: "transparent",
                    borderColor: "rgba(255, 255, 255, .8)",
                    borderColor: "rgba(255, 255, 255, .8)",
                    borderWidth: 4,
                    backgroundColor: "transparent",
                    fill: true,
                    data: monthlySalesFormatted,
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });

        var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

        var maxLabelLength = 10;

        // Guardamos etiquetas truncadas para mostrar en el eje X
        var truncatedLabels = topProductNames.map(name => 
            name.length > maxLabelLength ? name.substring(0, maxLabelLength) + "…" : name
        );

        // Usamos el original para el tooltip
        var originalLabels = topProductNames;
        var productSales = topProductSales;

        new Chart(ctx3, {
            type: "bar",
            data: {
                labels: truncatedLabels,
                datasets: [{
                    label: "Ventas",
                    data: productSales,
                    backgroundColor: [
                        "rgba(255, 255, 255, .8)",
                        "rgba(255, 255, 255, .8)",
                        "rgba(255, 255, 255, .8)",
                        "rgba(255, 255, 255, .8)",
                        "rgba(255, 255, 255, .8)"
                    ],
                    borderColor: [
                        "rgba(255, 255, 255, .8)",
                        "rgba(255, 255, 255, .8)",
                        "rgba(255, 255, 255, .8)",
                        "rgba(255, 255, 255, .8)",
                        "rgba(255, 255, 255, .8)"
                    ],
                    borderWidth: 1,
                    maxBarThickness: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function (context) {
                                const index = context.dataIndex;
                                return `${originalLabels[index]}: ${context.raw} ventas`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            }
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            }
                        }
                    }
                }
            }
        });

    </script>
  <!-- Github buttons -->
  <!-- <script async defer src="https://buttons.github.io/buttons.js"></script> -->
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
</body>

</html>