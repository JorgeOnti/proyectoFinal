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
						<h1>
							<?php
							// Conexión a la base de datos
							$host = 'localhost';
							$dbname = 'tienda-pi'; // Cambia al nombre de tu base de datos
							$username = 'root'; // Cambia si tienes otro usuario
							$password = ''; // Cambia si tu usuario tiene contraseña
							
							try {
								$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
								$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

								// Consulta para obtener el nombre, descripción y foto del producto destacado
								$stmt = $pdo->query("SELECT nombre, descripcion, foto FROM Productos WHERE id_producto = 1");
								$producto = $stmt->fetch(PDO::FETCH_ASSOC);

								// Mostrar el nombre del producto si existe
								echo htmlspecialchars($producto['nombre'] ?? 'Producto no disponible');
							} catch (PDOException $e) {
								echo "Error: " . $e->getMessage();
							}
							?>
						</h1>
						<p class="mb-4">
							<?php
							// Mostrar la descripción si existe
							echo htmlspecialchars($producto['descripcion'] ?? 'Descripción no disponible.');
							?>
						</p>
						<p>
						<form method="POST" action="cart.php" style="display: inline;">
							<input type="hidden" name="action" value="add">
							<input type="hidden" name="id_producto" value="1">
							<button type="submit" class="btn btn-secondary me-2">Comprar ahora</button>
						</form>
						<a href="shop.php" class="btn btn-white-outline">Explorar</a>
						</p>

					</div>
				</div>
				<div class="col-lg-7">
					<div class="hero-img-wrap">
						<?php
						if (!empty($producto['foto'])) {
							echo '<img src="' . htmlspecialchars($producto['foto']) . '" class="img-fluid hero-img-custom" alt="Producto destacado">';
						} else {
							echo '<img src="images/default-product.jpg" class="img-fluid hero-img-custom" alt="Imagen no disponible">';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Hero Section -->

	<!-- Start Product Section -->
	<div class="product-section">
		<div class="container">
			<div class="row">

				<!-- Start Column 1 -->
				<div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
					<h2 class="mb-4 section-title">Hechos con un excelente material.</h2>
					<p class="mb-4">Atrevete a probarlos </p>
					<p><a href="shop.php" class="btn">Explorar</a></p>
				</div>
				<!-- End Column 1 -->

				<?php
				// Conexión a la base de datos
				$host = 'localhost';
				$dbname = 'tienda-pi'; // Cambia al nombre de tu base de datos
				$username = 'root'; // Cambia si tienes otro usuario
				$password = ''; // Cambia si tu usuario tiene contraseña
				
				try {
					$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					// Consultar información de los productos con ID 2 y 3
					$stmt = $pdo->query("SELECT id_producto, nombre, foto, precio FROM Productos WHERE id_producto IN (2, 3, 10)");
					$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
				} catch (PDOException $e) {
					echo "Error en la conexión: " . $e->getMessage();
				}
				?>

				<!-- Start Column 2 -->
				<?php if (isset($productos[0])): ?>
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<form method="POST" action="cart.php">
							<input type="hidden" name="action" value="add">
							<input type="hidden" name="id_producto"
								value="<?php echo htmlspecialchars($productos[0]['id_producto']); ?>">
							<button type="submit" class="product-item btn p-0 border-0 bg-transparent">
								<img src="<?php echo htmlspecialchars($productos[0]['foto']); ?>"
									class="img-fluid product-thumbnail">
								<h3 class="product-title"><?php echo htmlspecialchars($productos[0]['nombre']); ?></h3>
								<strong
									class="product-price">$<?php echo number_format($productos[0]['precio'], 2); ?></strong>
							</button>
						</form>
					</div>
				<?php endif; ?>
				<!-- End Column 2 -->

				<!-- Start Column 3 -->
				<?php if (isset($productos[1])): ?>
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<form method="POST" action="cart.php">
							<input type="hidden" name="action" value="add">
							<input type="hidden" name="id_producto"
								value="<?php echo htmlspecialchars($productos[1]['id_producto']); ?>">
							<button type="submit" class="product-item btn p-0 border-0 bg-transparent">
								<img src="<?php echo htmlspecialchars($productos[1]['foto']); ?>"
									class="img-fluid product-thumbnail">
								<h3 class="product-title"><?php echo htmlspecialchars($productos[1]['nombre']); ?></h3>
								<strong
									class="product-price">$<?php echo number_format($productos[1]['precio'], 2); ?></strong>
							</button>
						</form>
					</div>
				<?php endif; ?>
				<!-- End Column 3 -->


				<!-- Start Column 4 -->
				<?php if (isset($productos[2])): ?>
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<form method="POST" action="cart.php">
							<input type="hidden" name="action" value="add">
							<input type="hidden" name="id_producto"
								value="<?php echo htmlspecialchars($productos[2]['id_producto']); ?>">
							<button type="submit" class="product-item btn p-0 border-0 bg-transparent">
								<img src="<?php echo htmlspecialchars($productos[2]['foto']); ?>"
									class="img-fluid product-thumbnail">
								<h3 class="product-title"><?php echo htmlspecialchars($productos[2]['nombre']); ?></h3>
								<strong
									class="product-price">$<?php echo number_format($productos[2]['precio'], 2); ?></strong>
							</button>
						</form>
					</div>
				<?php endif; ?>
				<!-- End Column 4 -->

			</div>
		</div>
	</div>
	<!-- End Product Section -->

	<!-- Start Why Choose Us Section -->
	<div class="why-choose-section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-6">
					<h2 class="section-title">¿Por qué elegirnos?</h2>
					<p>Somos especialistas en calzado de fútbol. Nuestro compromiso es ofrecerte la mejor calidad,
						diseño y tecnología para que des lo mejor en cada partido.</p>

					<div class="row my-5">
						<div class="col-6 col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="images/truck.svg" alt="Envío rápido y seguro" class="imf-fluid">
								</div>
								<h3>Envío rápido y seguro</h3>
								<p>Te garantizamos entregas rápidas y seguras para que recibas tus zapatos a tiempo y
									sin problemas.</p>
							</div>
						</div>

						<div class="col-6 col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="images/bag.svg" alt="Variedad y diseño" class="imf-fluid">
								</div>
								<h3>Variedad y diseño</h3>
								<p>Contamos con una amplia selección de modelos diseñados para maximizar tu rendimiento
									en el campo.</p>
							</div>
						</div>

						<div class="col-6 col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="images/support.svg" alt="Asistencia 24/7" class="imf-fluid">
								</div>
								<h3>Asistencia personalizada</h3>
								<p>Nuestro equipo está disponible 24/7 para ayudarte a elegir el calzado perfecto y
									resolver cualquier duda.</p>
							</div>
						</div>

						<div class="col-6 col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="images/return.svg" alt="Devoluciones fáciles" class="imf-fluid">
								</div>
								<h3>Devoluciones sin complicaciones</h3>
								<p>Si no estás completamente satisfecho, ofrecemos devoluciones rápidas y sencillas para
									tu tranquilidad.</p>
							</div>
						</div>

					</div>
				</div>

				<div class="col-lg-5">
					<div class="img-wrap">
						<img src="images/fut.png" alt="Nuestros zapatos de fútbol" class="img-fluid">
					</div>
				</div>

			</div>
		</div>
	</div>
	<!-- End Why Choose Us Section -->


	<!-- Start Testimonial Slider -->
	<div class="testimonial-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 mx-auto text-center">
					<h2 class="section-title">Testimonios</h2>
				</div>
			</div>

			<div class="row justify-content-center">
				<div class="col-lg-12">
					<div class="testimonial-slider-wrap text-center">

						<div id="testimonial-nav">
							<span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
							<span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
						</div>

						<div class="testimonial-slider">

							<div class="item">
								<div class="row justify-content-center">
									<div class="col-lg-8 mx-auto">

										<div class="testimonial-block text-center">
											<blockquote class="mb-5">
												<p>&ldquo;Los mejores zapatos de fútbol que he usado. La calidad es
													impresionante y la comodidad es inigualable. Ahora puedo jugar al
													máximo nivel.&rdquo;</p>
											</blockquote>

											<div class="author-info">
												<div class="author-pic">
													<img src="images/person_1.jpg" alt="Carlos Sánchez"
														class="img-fluid">
												</div>
												<h3 class="font-weight-bold">Carlos Sánchez</h3>
												<span class="position d-block mb-3">Jugador de fútbol amateur</span>
											</div>
										</div>

									</div>
								</div>
							</div>
							<!-- END item -->

							<div class="item">
								<div class="row justify-content-center">
									<div class="col-lg-8 mx-auto">

										<div class="testimonial-block text-center">
											<blockquote class="mb-5">
												<p>&ldquo;Compré estos zapatos para mi hijo y está encantado. La
													durabilidad y el diseño superaron nuestras expectativas. ¡Gracias
													ONTI'S!&rdquo;</p>
											</blockquote>

											<div class="author-info">
												<div class="author-pic">
													<img src="images/person_2.jpg" alt="José Gómez" class="img-fluid">
												</div>
												<h3 class="font-weight-bold">José Gómez</h3>
												<span class="position d-block mb-3">Padre de un joven futbolista</span>
											</div>
										</div>

									</div>
								</div>
							</div>
							<!-- END item -->

							<div class="item">
								<div class="row justify-content-center">
									<div class="col-lg-8 mx-auto">

										<div class="testimonial-block text-center">
											<blockquote class="mb-5">
												<p>&ldquo;Soy entrenador de un equipo juvenil y todos mis jugadores
													están fascinados con estos zapatos. Brindan un excelente agarre y
													soporte durante los partidos.&rdquo;</p>
											</blockquote>

											<div class="author-info">
												<div class="author-pic">
													<img src="images/person_3.jpg" alt="Diego Martínez"
														class="img-fluid">
												</div>
												<h3 class="font-weight-bold">Diego Martínez</h3>
												<span class="position d-block mb-3">Entrenador de fútbol</span>
											</div>
										</div>

									</div>
								</div>
							</div>
							<!-- END item -->

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Testimonial Slider -->


	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/tiny-slider.js"></script>
	<script src="js/custom.js"></script>
</body>

</html>