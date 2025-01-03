<!-- /*
* Bootstrap 5
* Template Name: ONTI'S
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<?php
session_start();
// Inicializar subtotal y total

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['email'])) {
	header("Location: login.php");
	exit();
}

// Conexión a la base de datos
$host = 'localhost';
$dbname = 'tienda-pi'; // Cambia al nombre de tu base de datos
$username = 'root'; // Cambia si tienes otro usuario
$password = ''; // Cambia si tu usuario tiene contraseña

try {
	$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Obtener el ID del usuario registrado
	$stmt = $pdo->prepare("SELECT id_usuario FROM Usuarios WHERE correo_electronico = :email");
	$stmt->bindParam(':email', $_SESSION['email']);
	$stmt->execute();
	$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
	$id_usuario = $usuario['id_usuario'];

	// Manejo de acciones del carrito
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['action'])) {
			$id_producto = $_POST['id_producto'];
			$cantidad = $_POST['cantidad'] ?? 1;

			switch ($_POST['action']) {
				case 'add': // Agregar producto al carrito
					$id_producto = $_POST['id_producto'] ?? null;
					$cantidad = 1; // Cantidad predeterminada al agregar
					if ($id_producto) {
						if ($usuario) {
							$id_usuario = $usuario['id_usuario'];

							// Verifica si el producto ya está en el carrito
							$stmt = $pdo->prepare("SELECT cantidad FROM Carrito_Compras WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
							$stmt->bindParam(':id_usuario', $id_usuario);
							$stmt->bindParam(':id_producto', $id_producto);
							$stmt->execute();
							$item = $stmt->fetch(PDO::FETCH_ASSOC);

							if ($item) {
								// Si el producto ya está en el carrito, actualiza la cantidad
								$nueva_cantidad = $item['cantidad'] + $cantidad;
								$stmt = $pdo->prepare("UPDATE Carrito_Compras SET cantidad = :cantidad WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
								$stmt->bindParam(':cantidad', $nueva_cantidad);
								$stmt->bindParam(':id_usuario', $id_usuario);
								$stmt->bindParam(':id_producto', $id_producto);
								$stmt->execute();
							} else {
								// Si no está, lo agrega al carrito
								$stmt = $pdo->prepare("INSERT INTO Carrito_Compras (id_usuario, id_producto, cantidad) 
													VALUES (:id_usuario, :id_producto, :cantidad)");
								$stmt->bindParam(':id_usuario', $id_usuario);
								$stmt->bindParam(':id_producto', $id_producto);
								$stmt->bindParam(':cantidad', $cantidad);
								$stmt->execute();
							}
						}
					}

					// Redirigir al carrito
					header("Location: cart.php");
					exit();

				case 'update': // Actualizar cantidad de un producto
					$stmt = $pdo->prepare("UPDATE Carrito_Compras SET cantidad = :cantidad 
                                           WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
					$stmt->bindParam(':cantidad', $cantidad);
					$stmt->bindParam(':id_usuario', $id_usuario);
					$stmt->bindParam(':id_producto', $id_producto);
					$stmt->execute();
					break;

				case 'delete': // Eliminar producto del carrito
					$stmt = $pdo->prepare("DELETE FROM Carrito_Compras WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
					$stmt->bindParam(':id_usuario', $id_usuario);
					$stmt->bindParam(':id_producto', $id_producto);
					$stmt->execute();
					break;
			}
		}
	}

	// Obtener los productos en el carrito
	$stmt = $pdo->prepare("
		SELECT p.id_producto, p.nombre, p.foto, p.precio, c.cantidad, (p.precio * c.cantidad) AS total
		FROM Carrito_Compras c
		JOIN Productos p ON c.id_producto = p.id_producto
		WHERE c.id_usuario = :id_usuario
	");

	$stmt->bindParam(':id_usuario', $id_usuario);
	$stmt->execute();
	$carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
	<title>Zapatos de futbol</title>
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
						<h1>Carrito</h1>
					</div>
				</div>
				<div class="col-lg-7">

				</div>
			</div>
		</div>
	</div>
	<!-- End Hero Section -->


	<div class="untree_co-section before-footer-section">
		<div class="container">
			<div class="row mb-5">
				<!-- Botón de continuar comprando -->
				<div class="col-md-6">
					<a href="shop.php" class="btn btn-outline-black btn-lg py-3 btn-block">Continuar Comprando</a>
				</div>
				<form class="col-md-12" method="post">
					<div class="site-blocks-table">
						<table class="table">
							<thead>
								<tr>
									<th class="product-thumbnail">Imagen</th>
									<th class="product-name">Producto</th>
									<th class="product-price">Precio</th>
									<th class="product-quantity">Cantidad</th>
									<th class="product-total">Total</th>
									<th class="product-remove">Eliminar</th>
								</tr>
							</thead>
							<tbody id="cart-items">
								<?php $subtotal = 0; ?>
								<?php foreach ($carrito as $item): ?>
									<tr data-product-id="<?php echo $item['id_producto']; ?>">
										<td><img src="<?php echo htmlspecialchars($item['foto']); ?>" alt="Imagen"
												class="img-fluid"></td>
										<td class="product-name">
											<h2 class="h5 text-black"><?php echo htmlspecialchars($item['nombre']); ?></h2>
										</td>
										<td class="price">$<?php echo number_format($item['precio'], 2); ?></td>
										<td>
											<div class="d-flex justify-content-center align-items-center">
												<div class="input-group" style="max-width: 120px;">
													<button type="button"
														class="btn btn-outline-secondary decrease">-</button>
													<input type="text" class="form-control text-center quantity"
														value="<?php echo htmlspecialchars($item['cantidad']); ?>" min="1">
													<button type="button"
														class="btn btn-outline-secondary increase">+</button>
												</div>
											</div>
										</td>
										<td class="total">$<?php echo number_format($item['total'], 2); ?></td>
										<td>
											<form method="POST" class="d-inline">
												<input type="hidden" name="action" value="delete">
												<input type="hidden" name="id_producto"
													value="<?php echo $item['id_producto']; ?>">
												<button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
											</form>
										</td>
									</tr>
									<?php $subtotal += $item['total']; ?>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</form>
			</div>

			<div class="row">
				<!-- Subtotal y Total -->
				<div class="col-md-6 text-right">
					<div class="row mb-3">
						<div class="col-md-6">
							<span class="text-black">Subtotal</span>
						</div>
						<div class="col-md-6 text-right">
							<strong id="subtotal"
								class="text-black">$<?php echo number_format($subtotal, 2); ?></strong>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-6">
							<span class="text-black">Total</span>
						</div>
						<div class="col-md-6 text-right">
							<strong id="total"
								class="text-black">$<?php echo number_format($subtotal * 1.16, 2); ?></strong>
						</div>
					</div>

					<div class="text-right">
						<div class="col-md-6">
							<a href="checkout.php" class="btn btn-outline-black btn-lg py-3 btn-block">Ir a pagar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/tiny-slider.js"></script>
	<script src="js/custom.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", () => {
			const cartItems = document.getElementById("cart-items");
			const subtotalEl = document.getElementById("subtotal");
			const totalEl = document.getElementById("total");

			function updateCartRow(row, quantity) {
				const priceEl = row.querySelector(".price");
				const totalEl = row.querySelector(".total");

				const price = parseFloat(priceEl.textContent.replace("$", ""));
				const total = price * quantity;

				totalEl.textContent = `$${total.toFixed(2)}`;
				return total;
			}

			function updateCartTotals() {
				let subtotal = 0;
				cartItems.querySelectorAll("tr").forEach(row => {
					const totalEl = row.querySelector(".total");
					subtotal += parseFloat(totalEl.textContent.replace("$", ""));
				});

				const total = subtotal * 1.16;
				subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
				totalEl.textContent = `$${total.toFixed(2)}`;
			}

			cartItems.addEventListener("click", (e) => {
				if (e.target.classList.contains("increase") || e.target.classList.contains("decrease")) {
					const row = e.target.closest("tr");
					const quantityInput = row.querySelector(".quantity");
					let quantity = parseInt(quantityInput.value);

					if (e.target.classList.contains("increase")) {
						quantity++;
					} else if (e.target.classList.contains("decrease") && quantity > 1) {
						quantity--;
					}

					quantityInput.value = quantity;
					updateCartRow(row, quantity);
					updateCartTotals();

					// Actualizar en la base de datos con PHP
					const productId = row.dataset.productId;
					updateCartInDatabase(productId, quantity);
				}
			});

			cartItems.addEventListener("input", (e) => {
				if (e.target.classList.contains("quantity")) {
					const row = e.target.closest("tr");
					const quantityInput = e.target;

					let quantity = parseInt(quantityInput.value);
					if (isNaN(quantity) || quantity < 1) {
						quantity = 1;
					}

					quantityInput.value = quantity;
					updateCartRow(row, quantity);
					updateCartTotals();

					// Actualizar en la base de datos con PHP
					const productId = row.dataset.productId;
					updateCartInDatabase(productId, quantity);
				}
			});

			// Función para actualizar en la base de datos usando PHP
			function updateCartInDatabase(productId, quantity) {
				const formData = new FormData();
				formData.append("action", "update");
				formData.append("id_producto", productId);
				formData.append("cantidad", quantity);

				fetch("cart.php", {
					method: "POST",
					body: formData,
				})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
							console.log("Carrito actualizado en la base de datos.");
						} else {
							console.error("Error al actualizar el carrito:", data.message);
						}
					});
			}
		});
	</script>

</body>

</html>