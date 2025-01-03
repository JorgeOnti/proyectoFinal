<!-- /*
* Bootstrap 5
* Template Name: ONTI'S
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->


<?php
session_start();
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'tienda-pi';
$username = 'root';
$password = '';

try {
	$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Obtener productos de la base de datos
	$stmt = $pdo->query("SELECT id_producto, nombre, precio, descripcion, foto FROM productos");
	$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	die("Error en la conexión: " . $e->getMessage());
}
?>

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
	<title>Explorar - ONTI'S</title>
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
	<div class="hero" id="hero-section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-5">
					<div class="intro-excerpt">
						<h1 id="hero-title">Selecciona un producto</h1>
						<p id="hero-description">Haz clic en un producto para más detalles.</p>
						<p><strong id="hero-price">$0.00</strong></p>
						<form method="POST" action="cart.php" id="add-to-cart-form">
							<input type="hidden" name="id_producto" id="selected-product-id">
							<input type="hidden" name="action" value="add">
							<button type="submit" class="btn btn-secondary">Agregar al carrito</button>
						</form>

					</div>
				</div>
				<div class="col-lg-7">
					<div class="hero-img-wrap">
						<img id="hero-image" src="images/balon.jpg" class="img-fluid hero-img-custom"
							alt="Producto">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Hero Section -->

	<!-- JavaScript -->
	<script>
		function updateHero(nombre, descripcion, precio, foto, id) {
			document.getElementById('hero-title').textContent = nombre;
			document.getElementById('hero-description').textContent = descripcion;
			document.getElementById('hero-price').textContent = `$${parseFloat(precio).toFixed(2)}`;
			document.getElementById('hero-image').src = foto;
			document.getElementById('selected-product-id').value = id;
			document.getElementById('add-to-cart-form').style.display = 'block';
		}

		function checkSession() {
			// Verificar si el usuario tiene una sesión activa
			<?php if (!isset($_SESSION['email'])): ?>
				alert('Debes iniciar sesión para agregar productos al carrito.');
				window.location.href = 'login.php';
				return false;
			<?php endif; ?>
			return true;
		}
	</script>


	<!-- Start Product Section -->
	<div class="untree_co-section product-section before-footer-section">
		<div class="container">
			<div class="row">
				<?php foreach ($productos as $producto): ?>
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="#hero-section" onclick="updateHero(
				 '<?php echo htmlspecialchars($producto['nombre']); ?>',
				 '<?php echo htmlspecialchars($producto['descripcion']); ?>',
				 '<?php echo htmlspecialchars($producto['precio']); ?>',
				 '<?php echo htmlspecialchars($producto['foto']); ?>',
				 '<?php echo htmlspecialchars($producto['id_producto']); ?>'
			   )">
							<img src="<?php echo htmlspecialchars($producto['foto']); ?>"
								class="img-fluid product-thumbnail">
							<h3 class="product-title"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
							<strong class="product-price">$<?php echo number_format($producto['precio'], 2); ?></strong>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<!-- End Product Section -->

	<!-- JavaScript -->
	<script>
		function updateHero(nombre, descripcion, precio, foto, id) {
			document.getElementById('hero-title').textContent = nombre;
			document.getElementById('hero-description').textContent = descripcion;
			document.getElementById('hero-price').textContent = `$${parseFloat(precio).toFixed(2)}`;
			document.getElementById('hero-image').src = foto;
			document.getElementById('selected-product-id').value = id;
			document.getElementById('add-to-cart-form').style.display = 'block';
		}
	</script>
	<script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>