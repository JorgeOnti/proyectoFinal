<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="ONTI'S">
	<link rel="shortcut icon" href="favicon.png">

	<meta name="description" content="" />
	<meta name="keywords" content="fútbol, tienda deportiva, zapatos deportivos" />

	<!-- Bootstrap CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link href="css/tiny-slider.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<title>Contacto - ONTI'S Zapatos de Fútbol</title>
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
						<h1>Contacto</h1>
						<p class="mb-4">¿Tienes alguna pregunta sobre nuestros zapatos de fútbol? Contáctanos, estamos
							aquí para ayudarte.</p>
						<p><a href="shop.php" class="btn btn-secondary me-2">Explorar</a><a href="about.php"
								class="btn btn-white-outline">Sobre Nosotros</a></p>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="hero-img-wrap">
						<img src="images/football-contact.jpg" class="img-fluid" alt="Contacto fútbol">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Hero Section -->

	<!-- Start Contact Form -->
	<div class="untree_co-section">
		<div class="container">
			<div class="block">
				<div class="row justify-content-center">
					<div class="col-md-8 col-lg-8 pb-4">
						<form id="contact-form">
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label class="text-black" for="fname">Nombre</label>
										<input type="text" class="form-control" id="fname" required>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label class="text-black" for="lname">Apellido</label>
										<input type="text" class="form-control" id="lname" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="text-black" for="email">Correo Electrónico</label>
								<input type="email" class="form-control" id="email" required>
							</div>
							<div class="form-group mb-5">
								<label class="text-black" for="message">Mensaje</label>
								<textarea name="" class="form-control" id="message" cols="30" rows="5"
									required></textarea>
							</div>
							<button type="submit" class="btn btn-primary-hover-outline">Enviar Mensaje</button>
						</form>

						<!-- Mensaje de agradecimiento -->
						<div id="thank-you-message" class="alert alert-success mt-4 d-none">
							<strong>¡Gracias!</strong> Hemos recibido tu mensaje. Nos pondremos en contacto contigo a la
							brevedad.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Contact Form -->

	<script>
		document.getElementById("contact-form").addEventListener("submit", function (e) {
			e.preventDefault(); // Evitar envío real del formulario

			// Mostrar mensaje de agradecimiento
			document.getElementById("thank-you-message").classList.remove("d-none");

			// Limpiar los campos del formulario
			document.getElementById("contact-form").reset();
		});
	</script>
	<script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>