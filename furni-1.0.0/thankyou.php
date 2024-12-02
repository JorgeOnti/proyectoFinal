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
	<meta name="author" content="Untree.co">
	<link rel="shortcut icon" href="favicon.png">

	<meta name="description" content="" />
	<meta name="keywords" content="bootstrap, bootstrap4" />

	<!-- Bootstrap CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link href="css/tiny-slider.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<title>ONTI'S Free Bootstrap 5 Template for ONTI'Sture and Interior Design Websites by Untree.co </title>
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
							<a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button"
								data-bs-toggle="dropdown" aria-expanded="false">
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
						<h1>Cart</h1>
					</div>
				</div>
				<div class="col-lg-7">

				</div>
			</div>
		</div>
	</div>
	<!-- End Hero Section -->



	<div class="untree_co-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center pt-5">
					<span class="display-3 thankyou-icon text-primary">
						<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cart-check mb-5"
							fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd"
								d="M11.354 5.646a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L8 8.293l2.646-2.647a.5.5 0 0 1 .708 0z" />
							<path fill-rule="evenodd"
								d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
						</svg>
					</span>
					<h2 class="display-3 text-black">Gracias por su compra!</h2>
					<p class="lead mb-5">Su orden fue registrada exitosamente.</p>
					<p><a href="shop.php" class="btn btn-sm btn-outline-black">Seguir Comprando</a></p>
				</div>
			</div>
		</div>
	</div>

	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/tiny-slider.js"></script>
	<script src="js/custom.js"></script>
</body>

</html>