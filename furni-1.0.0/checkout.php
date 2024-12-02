<?php
session_start();

if (!isset($_SESSION['email'])) {
	header("Location: login.php");
	exit();
}

// Conexión a la base de datos
$host = 'localhost';
$dbname = 'tienda-pi';
$username = 'root';
$password = '';

try {
	$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $pdo->prepare("SELECT id_usuario FROM Usuarios WHERE correo_electronico = :email");
	$stmt->bindParam(':email', $_SESSION['email']);
	$stmt->execute();
	$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$usuario) {
		die("Usuario no encontrado.");
	}

	$id_usuario = $usuario['id_usuario'];

	$stmt = $pdo->prepare("
        SELECT p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) AS total
        FROM Carrito_Compras c
        JOIN Productos p ON c.id_producto = p.id_producto
        WHERE c.id_usuario = :id_usuario
    ");
	$stmt->bindParam(':id_usuario', $id_usuario);
	$stmt->execute();
	$carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$subtotal = 0;
	foreach ($carrito as $item) {
		$subtotal += $item['total'];
	}
	$impuestos = $subtotal * 0.16;
	$total = $subtotal + $impuestos;

} catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
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
	<script
		src="https://www.paypal.com/sdk/js?client-id=Acw9zqUJ_XtHbdNzWTWvFpeeUrlHRm24Pdlop8JcUTEOuAqEqxxZK08Ow0-purrPRttX25wp1M_lxS2F&currency=USD"></script>

	<!-- Bootstrap CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<title>Checkout</title>
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

	<!-- Hero Section -->
	<div class="hero">
		<div class="container">
			<h1>Checkout</h1>
		</div>
	</div>

	<!-- Checkout Section -->
	<div class="untree_co-section">
		<div class="container">
			<form id="checkout-form" method="POST" action="register_purchase.php">
				<div class="row">
					<div class="col-md-6">
						<h2 class="h3 text-black">Detalles de Envío</h2>
						<div class="p-3 border bg-white">
							<?php if (isset($error)): ?>
								<div class="alert alert-danger"><?php echo $error; ?></div>
							<?php elseif (isset($success)): ?>
								<div class="alert alert-success"><?php echo $success; ?></div>
							<?php endif; ?>
							<div class="form-group">
								<label for="c_address">Dirección <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="c_address" name="c_address" required>
							</div>
							<div class="form-group">
								<label for="c_city">Ciudad <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="c_city" name="c_city" required>
							</div>
							<div class="form-group">
								<label for="c_postal_zip">Código Postal <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="c_postal_zip" name="c_postal_zip" required>
							</div>
							<div class="form-group">
								<label for="c_state_country">País <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="c_state_country" name="c_state_country"
									required>
							</div>
							<div class="form-group mt-3">
								<label><input type="checkbox" name="guardar_direccion" value="1"> Guardar esta
									dirección</label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<h2 class="h3 text-black">Tu Orden</h2>
						<div class="p-3 border bg-white">
							<table class="table">
								<thead>
									<tr>
										<th>Producto</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($carrito as $item): ?>
										<tr>
											<td><?php echo htmlspecialchars($item['nombre']); ?>
												x<?php echo $item['cantidad']; ?></td>
											<td>$<?php echo number_format($item['total'], 2); ?></td>
										</tr>
									<?php endforeach; ?>
									<tr>
										<td><strong>Subtotal</strong></td>
										<td>$<?php echo number_format($subtotal, 2); ?></td>
									</tr>
									<tr>
										<td><strong>Impuestos (16%)</strong></td>
										<td>$<?php echo number_format($impuestos, 2); ?></td>
									</tr>
									<tr>
										<td><strong>Total</strong></td>
										<td>$<?php echo number_format($total, 2); ?></td>
									</tr>
								</tbody>
							</table>
							<div id="paypal-button-container"></div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script>
		function validateForm() {
			const address = document.getElementById('c_address').value;
			const city = document.getElementById('c_city').value;
			const postalZip = document.getElementById('c_postal_zip').value;
			const stateCountry = document.getElementById('c_state_country').value;

			if (!address || !city || !postalZip || !stateCountry) {
				alert('Por favor, complete todos los campos obligatorios.');
				return false;
			}
			return true;
		}

		paypal.Buttons({
			createOrder: function (data, actions) {
				if (!validateForm()) {
					return false;
				}
				return actions.order.create({
					purchase_units: [{
						amount: { value: '<?php echo number_format($total, 2, '.', ''); ?>' }
					}]
				});
			},
			onApprove: function (data, actions) {
				return actions.order.capture().then(function (details) {
					// Enviar el formulario
					document.getElementById('checkout-form').submit();
				});
			},
			onError: function (err) {
				console.error('PayPal Checkout Error: ', err);
			}
		}).render('#paypal-button-container');
	</script>
</body>

</html>