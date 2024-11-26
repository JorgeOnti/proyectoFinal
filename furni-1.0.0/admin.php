<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
$host = 'localhost';
$dbname = 'tienda-pi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el usuario es administrador
    $stmt = $pdo->prepare("SELECT es_administrador FROM Usuarios WHERE correo_electronico = :email");
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['es_administrador'] != 1) {
        // Si no es administrador, redirigir a la página principal
        header("Location: index.php");
        exit();
    }

    // Función para obtener productos
    $productos = $pdo->query("SELECT * FROM Productos")->fetchAll(PDO::FETCH_ASSOC);

    // Función para obtener historial de compras
    $query = "
        SELECT 
            historial_compras.id_compra, 
            usuarios.correo_electronico AS usuario, 
            productos.nombre AS producto, 
            historial_compras.cantidad, 
            historial_compras.fecha_compra
        FROM 
            historial_compras
        JOIN 
            usuarios ON historial_compras.id_usuario = usuarios.id_usuario
        JOIN 
            productos ON historial_compras.id_producto = productos.id_producto
        ORDER BY 
            historial_compras.fecha_compra DESC
    ";
    $stmt = $pdo->query($query);
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);



    // Agregar nuevo producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $foto = $_POST['foto'];

        $stmt = $pdo->prepare("INSERT INTO Productos (nombre, descripcion, precio, cantidad, foto) VALUES (:nombre, :descripcion, :precio, :cantidad, :foto)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':foto', $foto);
        $stmt->execute();

        header("Location: admin.php");
        exit();
    }

    // Modificar producto existente
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
        $id_producto = $_POST['id_producto'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $foto = $_POST['foto'];

        $stmt = $pdo->prepare("UPDATE Productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, cantidad = :cantidad, foto = :foto WHERE id_producto = :id_producto");
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':foto', $foto);
        $stmt->execute();

        header("Location: admin.php");
        exit();
    }

} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-primary">Regresar a la página principal</a>
    </div>
    <div class="container mt-5">
        <h1 class="mb-4">Panel de Administración</h1>

        <!-- Reporte de productos en inventario -->
        <h2>Productos en Inventario</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['id_producto']; ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td><?php echo $producto['cantidad']; ?></td>
                        <td><img src="<?php echo htmlspecialchars($producto['foto']); ?>" alt="Imagen" width="50"></td>
                        <td>
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" placeholder="Nombre">
                                <input type="text" name="descripcion" value="<?php echo htmlspecialchars($producto['descripcion']); ?>" placeholder="Descripción">
                                <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" placeholder="Precio">
                                <input type="number" name="cantidad" value="<?php echo $producto['cantidad']; ?>" placeholder="Cantidad">
                                <input type="text" name="foto" value="<?php echo htmlspecialchars($producto['foto']); ?>" placeholder="URL Foto">
                                <button type="submit" name="update_product" class="btn btn-primary btn-sm">Actualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulario para agregar productos -->
        <h2>Agregar Producto</h2>
        <form method="POST">
            <input type="hidden" name="add_product" value="1">
            <div class="mb-3">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre del Producto" required>
            </div>
            <div class="mb-3">
                <textarea name="descripcion" class="form-control" placeholder="Descripción" required></textarea>
            </div>
            <div class="mb-3">
                <input type="number" step="0.01" name="precio" class="form-control" placeholder="Precio" required>
            </div>
            <div class="mb-3">
                <input type="number" name="cantidad" class="form-control" placeholder="Cantidad" required>
            </div>
            <div class="mb-3">
                <input type="text" name="foto" class="form-control" placeholder="URL de la Foto" required>
            </div>
            <button type="submit" class="btn btn-success">Agregar Producto</button>
        </form>

        <!-- Historial de compras -->
        <h2 class="mt-5">Historial de Compras</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Compra</th>
                    <th>Usuario</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $compra): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($compra['id_compra']); ?></td>
                        <td><?php echo htmlspecialchars($compra['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($compra['producto']); ?></td>
                        <td><?php echo htmlspecialchars($compra['cantidad']); ?></td>
                        <td><?php echo htmlspecialchars($compra['fecha_compra']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

