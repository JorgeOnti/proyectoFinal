<!-- /*
* Bootstrap 5
* Template Name: ONTI'S
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="ONTI'S">
  <link rel="shortcut icon" href="favicon.png">

  <meta name="description" content="" />
  <meta name="keywords" content="fútbol, zapatos deportivos, tienda deportiva" />

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/tiny-slider.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <title>Acerca de ONTI'S - Zapatos de Fútbol</title>
</head>

<body>

  <!-- Start Header/Navigation -->
  <nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="ONTI'S navigation bar">
    <div class="container">
      <a class="navbar-brand" href="index.php">ONTI'S<span>.</span></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsONTI'S"
        aria-controls="navbarsONTI'S" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsONTI'S">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Inicio</a>
          </li>
          <li><a class="nav-link" href="shop.php">Explorar</a></li>
          <li><a class="nav-link" href="about.php">About us</a></li>
          <li><a class="nav-link" href="contact.php">Contact us</a></li>

          <!-- Verificar si el usuario es administrador -->
          <?php
          if (session_status() === PHP_SESSION_NONE) {
            session_start();
          }

          if (!isset($_SESSION['email'])) {
            header("Location: login.php");
            exit();
          }

          if (isset($_SESSION['email'])):
            // Conectar a la base de datos
            $host = 'localhost';
            $dbname = 'tienda-pi';
            $username = 'root';
            $password = '';

            try {
              $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              // Obtener si el usuario es administrador
              $stmt = $pdo->prepare("SELECT es_administrador FROM usuarios WHERE correo_electronico = :email");
              $stmt->bindParam(':email', $_SESSION['email']);
              $stmt->execute();
              $user = $stmt->fetch(PDO::FETCH_ASSOC);

              if ($user && $user['es_administrador'] == 1): ?>
                <li><a class="nav-link" href="admin.php">Admin</a></li>
              <?php endif;
            } catch (PDOException $e) {
              echo "Error: " . $e->getMessage();
            }
          endif;
          ?>
        </ul>

        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
          <!-- Verificar si el usuario ha iniciado sesión -->
          <?php if (isset($_SESSION['email'])): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <img src="images/user.svg" alt="User">
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
                <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li>
              <a class="nav-link" href="login.php"><img src="images/user.svg" alt="User"> Iniciar sesión</a>
            </li>
          <?php endif; ?>
          <li><a class="nav-link" href="cart.php"><img src="images/cart.svg" alt="Cart"></a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Header/Navigation -->

  <!-- Start Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>Sobre Nosotros</h1>
            <p class="mb-4">ONTI'S es una tienda especializada en zapatos de fútbol. Creemos en brindar productos de la
              más alta calidad para jugadores de todos los niveles.</p>
            <p><a href="shop.php" class="btn btn-secondary me-2">Comprar ahora</a></p>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="hero-img-wrap">
            <img src="images/football-shoes.png" class="img-fluid" alt="Zapatos de fútbol">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <!-- Start Why Choose Us Section -->
  <div class="why-choose-section">
    <div class="container">
      <div class="row justify-content-between align-items-center">
        <div class="col-lg-6">
          <h2 class="section-title">Por qué elegirnos</h2>
          <p>En ONTI'S, nos apasiona el fútbol y ofrecemos productos diseñados para maximizar tu rendimiento en la
            cancha.</p>
          <div class="row my-5">
            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/truck.svg" alt="Envíos rápidos" class="img-fluid">
                </div>
                <h3>Envíos rápidos</h3>
                <p>Entrega garantizada para que estés listo para tu próximo partido.</p>
              </div>
            </div>
            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/quality.svg" alt="Alta calidad" class="img-fluid">
                </div>
                <h3>Alta calidad</h3>
                <p>Zapatos diseñados para durar y adaptarse a cualquier terreno.</p>
              </div>
            </div>
            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/support.svg" alt="Soporte 24/7" class="img-fluid">
                </div>
                <h3>Soporte 24/7</h3>
                <p>Estamos aquí para responder tus preguntas cuando las necesites.</p>
              </div>
            </div>
            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/return.svg" alt="Devoluciones fáciles" class="img-fluid">
                </div>
                <h3>Devoluciones fáciles</h3>
                <p>Compra sin preocupaciones con nuestra política de devoluciones.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="img-wrap">
            <img src="images/football-action.jpg" alt="Fútbol en acción" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Why Choose Us Section -->

  <!-- Start Team Section -->
  <div class="untree_co-section">
    <div class="container">
      <div class="row mb-5">
        <div class="col-lg-5 mx-auto text-center">
          <h2 class="section-title">Nuestro equipo</h2>
        </div>
      </div>
      <div class="row">
        <!-- Los miembros del equipo pueden seguir igual o ajustarse según lo desees -->
      </div>
    </div>
  </div>
  <!-- End Team Section -->


  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/tiny-slider.js"></script>
  <script src="js/custom.js"></script>
</body>

</html>