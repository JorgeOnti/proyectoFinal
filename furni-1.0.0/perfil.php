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

    // Obtener ID del usuario
    $stmt = $pdo->prepare("SELECT id_usuario, nombre, correo_electronico FROM Usuarios WHERE correo_electronico = :email");
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Usuario no encontrado.");
    }

    $id_usuario = $usuario['id_usuario'];

    // Obtener historial de compras
    $stmt = $pdo->prepare("
        SELECT p.nombre, p.precio, h.cantidad, (p.precio * h.cantidad) AS total, h.fecha_compra
        FROM Historial_Compras h
        JOIN Productos p ON h.id_producto = p.id_producto
        WHERE h.id_usuario = :id_usuario
    ");
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener direcciones guardadas
    $stmt = $pdo->prepare("
        SELECT direccion, ciudad, codigo_postal, pais 
        FROM Direcciones 
        WHERE usuario_id = :id_usuario
    ");
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $direcciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <title>Perfil</title>
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

    <!-- Perfil Section -->
    <div class="container mt-5">
        <h1>Perfil de Usuario</h1>
        <div class="mb-4">
            <h4>Información Personal</h4>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($usuario['correo_electronico']); ?></p>
        </div>

        <!-- Direcciones Guardadas -->
        <div class="mb-4">
            <h4>Direcciones Guardadas</h4>
            <?php if (count($direcciones) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($direcciones as $direccion): ?>
                        <li class="list-group-item">
                            <?php echo htmlspecialchars($direccion['direccion']); ?>, 
                            <?php echo htmlspecialchars($direccion['ciudad']); ?>, 
                            <?php echo htmlspecialchars($direccion['codigo_postal']); ?>, 
                            <?php echo htmlspecialchars($direccion['pais']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No tienes direcciones guardadas.</p>
            <?php endif; ?>
        </div>

        <!-- Historial de Compras -->
        <div class="mb-4">
            <h4>Historial de Compras</h4>
            <?php if (count($historial) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Fecha de Compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $compra): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($compra['nombre']); ?></td>
                                <td><?php echo $compra['cantidad']; ?></td>
                                <td>$<?php echo number_format($compra['total'], 2); ?></td>
                                <td><?php echo htmlspecialchars($compra['fecha_compra']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No tienes compras registradas.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>
