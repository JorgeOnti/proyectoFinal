<?php
session_start();

// Verificar si el usuario ha iniciado sesión
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

    // Obtener el ID del usuario
    $stmt = $pdo->prepare("SELECT id_usuario FROM Usuarios WHERE correo_electronico = :email");
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_usuario = $usuario['id_usuario'];

    // Insertar en el historial de compras
    $stmt = $pdo->prepare("
        SELECT c.id_producto, c.cantidad
        FROM Carrito_Compras c
        WHERE c.id_usuario = :id_usuario
    ");
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($productos as $producto) {
        $stmt = $pdo->prepare("INSERT INTO Historial_Compras (id_usuario, id_producto, cantidad) 
                               VALUES (:id_usuario, :id_producto, :cantidad)");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_producto', $producto['id_producto']);
        $stmt->bindParam(':cantidad', $producto['cantidad']);
        $stmt->execute();

        // Actualizar el inventario del producto
        $stmt = $pdo->prepare("UPDATE Productos SET cantidad = cantidad - :cantidad 
                               WHERE id_producto = :id_producto");
        $stmt->bindParam(':cantidad', $producto['cantidad']);
        $stmt->bindParam(':id_producto', $producto['id_producto']);
        $stmt->execute();
    }

    // Vaciar el carrito del usuario
    $stmt = $pdo->prepare("DELETE FROM Carrito_Compras WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();

    // Guardar dirección si se seleccionó la opción
    if (isset($_POST['guardar_direccion'])) {
        $direccion = $_POST['c_address'] ?? '';
        $ciudad = $_POST['c_city'] ?? '';
        $codigo_postal = $_POST['c_postal_zip'] ?? '';
        $pais = $_POST['c_state_country'] ?? '';

        if (!empty($direccion) && !empty($ciudad) && !empty($codigo_postal) && !empty($pais)) {
            $stmt = $pdo->prepare("INSERT INTO Direcciones (usuario_id, direccion, ciudad, codigo_postal, pais)
                                   VALUES (:usuario_id, :direccion, :ciudad, :codigo_postal, :pais)");
            $stmt->bindParam(':usuario_id', $id_usuario);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':ciudad', $ciudad);
            $stmt->bindParam(':codigo_postal', $codigo_postal);
            $stmt->bindParam(':pais', $pais);
            $stmt->execute();
        }
    }

    // Redirigir a la página de agradecimiento
    header("Location: thankyou.php");
    exit();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>