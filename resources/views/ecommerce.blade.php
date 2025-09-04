<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shopix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #fff;
      color: #000;
    }

    .navbar {
      background-color: #000 !important;
    }

    .navbar a,
    .navbar-toggler-icon {
      color: #000 !important;
    }

    .hero {
      position: relative;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
    }

    .hero video {
      position: absolute;
      top: 0;
      left: 0;
      object-fit: cover;
      width: 100%;
      height: 100%;
      z-index: -1;
    }

    .filter-box {
      background: white;
      border-radius: 12px;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
      padding: 20px;
    }

    .section-title {
      font-size: 2rem;
      font-weight: 700;
    }

    .card-product {
      border: none;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }

    .card-product:hover {
      transform: scale(1.01);
    }

    .icon-box {
      text-align: center;
    }

    .icon-box i {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    footer {
      background: #000;
      color: #fff;
      padding: 40px 0;
      text-align: center;
    }
    .glass-header {
      backdrop-filter: blur(16px) saturate(180%);
      -webkit-backdrop-filter: blur(16px) saturate(180%);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.25);
      color: white;
      width: 100%;
    }
    .nav-link.white-shadow {
      color: white;
      text-shadow: 1px 1px 3px rgba(1, 0, 0, 2);
    }
    .glass-text {
      font-size: 5rem;
      font-weight: 700;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.91), rgba(255, 255, 255, 0.86));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 1px 2px rgba(255, 255, 255, 0.75), 0 4px 8px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body>

<div class="container d-flex justify-content-center position-fixed top-0 start-50 translate-middle-x" style="z-index: 1050; margin-top: 30px;">
  <div class="card glass-header px-4 py-3 w-100" style="max-width: 90vw;">
    <div class="row align-items-center">
      
      <!-- Menú -->
      <div class="col-md-4 d-flex justify-content-start">
        <ul class="nav">
          <a class="nav-link fw-semibold white-shadow" href="#beneficios">Beneficios</a>
          <a class="nav-link fw-semibold white-shadow" href="#contacto">Contacto</a>
          <a class="nav-link fw-semibold white-shadow" href="#contacto">Ubicación</a>
        </ul>
      </div>

      <!-- Logo -->
      <div class="col-md-4 d-flex justify-content-center">
        <img src="../../assets/img/inf.png" alt="Logo" class="img-fluid" style="height: 60px; filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));">
      </div>

      <!-- Botón -->
      <div class="col-md-4 d-flex justify-content-end">
        <a href="http://192.168.1.119:3000/" class="btn btn-light text-dark fw-bold px-4 py-2">Comprar</a>
      </div>

    </div>
  </div>
</div>



  <!-- HERO CON VIDEO DE FONDO -->
  <section class="hero">
    <video autoplay muted loop playsinline>
      <source src="../../assets/img/Street.mp4" type="video/mp4" />
    </video>
    <div class="container text-center">
      <h1 class="glass-text">SHOPIX</h1>
      <h1 class="glass-text">MODA PARA HOY Y SIEMPRE</h1>
      <p class="lead glass-text" style="font-size: 1.5rem;">Chemises, franelas y jeans que definen tu estilo</p>
      <!-- Botón -->
        <a href="http://192.168.1.119:3000/" class="btn btn-light text-dark fw-bold px-4 py-2 w-50">Comprar ahora</a>
    </div>
  </section>

  <!-- COLECCIÓN DESTACADA -->
  <section class="py-5">
    <div class="container">
      <h2 class="section-title mb-4 text-center">Nuestra Colección</h2>
      <div class="row g-4">
      @foreach($categories as $category)
      <div class="col-md-4">
        <div class="card card-product">
          <div class="text-center mt-4">
            <i class="{{ $category->icon }} fs-2"></i>
          </div>
          <div class="card-body text-center">
            <h6 class="text-center mb-0 opacity-9">{{ $category->name }}</h6>
            <span class="text-xs">{{ $category->description }}</span>
          </div>
        </div>
      </div>
      @endforeach
      </div>
    </div>
  </section>
<!-- SECCIÓN DE CALIDAD -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="section-title mb-5">Calidad Garantizada</h2>
    <div class="row justify-content-center">
      @foreach($productItems as $product)
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          @if(isset($product->images) && count($product->images) > 0)
            <img src="{{ asset('storage/' . $product->images[0]->path) }}" 
                 class="card-img-top" 
                 alt="Imagen del producto"
                 style="height: 300px; object-fit: cover;">
          @else
            <div class="d-flex align-items-center justify-content-center" 
                 style="height: 300px; background-color: #eee;">
              <i class="material-symbols-rounded text-muted fs-1">photo_camera</i>
            </div>
          @endif
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">{{ $product->name }}</h5>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <p class="mt-4">Confeccionamos cada pieza con materiales premium.</p>
  </div>
</section>


  <!-- BENEFICIOS -->
  <section class="py-5">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-4 icon-box">
          <i class="bi bi-box-seam"></i>
          <h5>Hecho a Pedido</h5>
          <p>Ropa exclusiva confeccionada para ti</p>
        </div>
        <div class="col-md-4 icon-box">
          <i class="bi bi-truck"></i>
          <h5>Envío Gratis</h5>
          <p>En pedidos a nivel nacional</p>
        </div>
        <div class="col-md-4 icon-box">
          <i class="bi bi-arrow-left-right"></i>
          <h5>Devoluciones Fáciles</h5>
          <p>Cambios disponibles sin costo adicional</p>
        </div>
      </div>
    </div>
  </section>

<!-- UBICACIÓN -->
<section id="contacto" class="py-5 bg-light">
  <div class="container">
    <div class="row align-items-center">
      
      <!-- Texto y botón -->
      <div class="col-md-6 mb-4 mb-md-0">
        <h2 class="section-title mb-3">Dónde nos ubicamos</h2>
        <p class="mb-3">Calle Sucre - Edificio Maturín PB Local 01</p>
        <a href="https://www.google.com/maps/@9.7527562,-63.1679763,15.25z?entry=ttu" target="_blank" class="btn btn-dark px-4 py-2">
          Ver en Google Maps
        </a>

        <!-- Redes sociales -->
        <div class="mt-4 d-flex gap-5">
          <a href="https://www.instagram.com/infinitycenter.ca/" target="_blank" class="text-dark fs-4">
            <i class="bi bi-instagram"></i>
          </a>
          <a href="https://api.whatsapp.com/send?phone=584122628765" target="_blank" class="text-dark fs-4">
            <i class="bi bi-whatsapp"></i>
          </a>
          <a href="https://facebook.com" target="_blank" class="text-dark fs-4">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="https://t.me" target="_blank" class="text-dark fs-4">
            <i class="bi bi-telegram"></i>
          </a>
        </div>
      </div>

      <!-- Mapa -->
      <div class="col-md-6">
        <div class="rounded-4 overflow-hidden shadow" style="height: 300px;">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d62734.306809791316!2d-63.1679763!3d9.7527562!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sve!4v1700000000000"
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>

    </div>
  </div>
</section>



  <!-- FOOTER -->
  <footer>
    <div class="container">
      <p>© 2025 Shopix. Todos los derechos reservados.</p>
      <a href="http://192.168.1.119:8000/login" class="btn btn-light text-dark fw-bold px-4 py-2 w-50">Soy Admin</a>

    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</body>

</html>