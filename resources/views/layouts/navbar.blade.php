<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
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
  <link href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="/dashboard" target="_blank">
        <img src="../../assets/img/hc.png" class="navbar-brand-img" width="40" height="40" alt="main_logo">
        <span class="ms-1 text-sm text-dark">El Hombre Casual</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="/dashboard">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Administrador</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="/categories">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <!-- <i class="bi bi-bag"></i> -->
            <span class="nav-link-text ms-1">Categorías</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="/products">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Productos</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="/paymentMethods">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <!-- <i class="bi bi-bag"></i> -->
            <span class="nav-link-text ms-1">Métodos de Pago</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="/sales">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Realizar Venta</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="/sales-orders">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text ms-1">Ventas Realizadas</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="/purchase">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <!-- <i class="bi bi-bag"></i> -->
            <span class="nav-link-text ms-1">Realizar Compra</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="/purchase-orders">
            <i class="material-symbols-rounded opacity-5">format_textdirection_r_to_l</i>
            <span class="nav-link-text ms-1">Compras Realizadas</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Usuarios</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="/users">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Gestión de usuarios</span>
          </a>
        </li>
        <li class="nav-item d-flex" onclick="logOut()">
          <a class="nav-link text-dark">
            <!-- <i class="bi bi-person-circle"></i> -->
            <i class="material-symbols-rounded opacity-5">supervised_user_circle</i>
            <span class="nav-link-text ms-1">Cerrar Sesión</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
      </div>
    </div>
  </aside>
<!-- Core JS Files -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>

  <script>
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script>
document.addEventListener("DOMContentLoaded", function () {
    const currentUrl = window.location.pathname; // Obtén la ruta actual sin el dominio
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link'); // Selecciona los enlaces

    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        if (currentUrl === linkHref) { // Compara la ruta actual con el href
            link.classList.add("bg-gradient-info", "text-white");
        } else {
            link.classList.remove("bg-gradient-info", "text-white");
        }
    });
});

  function logOut() {
    fetch("/api/logout", { // Ajusta a `/api/logout` si es una ruta API.
        method: 'POST',
        // headers: {
        //     'Content-Type': 'application/json',
        //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Obtener el token CSRF de la meta etiqueta
        // }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Logout failed');
        }
    })
    .then(data => {
        console.log("Logged out successfully:", data);
        // localStorage.removeItem('authToken');
        window.location.href = '/login'; // Redirigir a la página de inicio de sesión.
    })
    .catch(error => {
        console.error("Error during logout:", error);
        alert("Ocurrió un error al cerrar sesión.");
    });
}
</script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="{{ asset('assets/js/navbar.js') }}"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
</body>

</html>