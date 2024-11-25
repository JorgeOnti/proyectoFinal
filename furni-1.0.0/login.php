<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    $host = 'localhost';
    $dbname = 'tienda-pi'; // Cambia al nombre de tu base de datos
    $username = 'root'; // Cambia si tienes otro usuario
    $password = ''; // Cambia si tu usuario tiene contraseña

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Recibir datos del formulario
        $correo = $_POST['user'] ?? '';
        $clave = $_POST['clave'] ?? '';

        // Consulta para verificar credenciales
        $stmt = $pdo->prepare("SELECT id_usuario, contrasena FROM Usuarios WHERE correo_electronico = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($clave, $usuario['contrasena'])) {
            // Credenciales correctas, iniciar sesión
            $_SESSION['email'] = $correo;
            header("Location: index.php");
            exit();
        } else {
            // Credenciales incorrectas
            $error = "Usuario o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        $error = "Error en la conexión: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <!-- Importamos los estilos de Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
          <div class="spacing-1"></div>
          <fieldset>
            <legend class="center">Login</legend>

            <!-- Mostrar mensaje de error -->
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
              </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form method="POST" action="login.php">
              <label class="sr-only" for="user">Usuario</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                <input type="text" class="form-control" id="user" name="user" placeholder="Ingresa tu usuario" required>
              </div>
              <div class="spacing-2"></div>
              <label class="sr-only" for="clave">Contraseña</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                <input type="password" autocomplete="off" class="form-control" id="clave" name="clave" placeholder="Ingresa tu contraseña" required>
              </div>
              <div class="spacing-2"></div>
              <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
            </form>

            <section class="text-accent center">
              <div class="spacing-2"></div>
              <p>No tienes una cuenta? <a href="signup.php">¡Regístrate!</a></p>
            </section>
          </fieldset>
        </div>
      </div>
    </div>
  </body>
</html>
