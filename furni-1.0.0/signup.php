<?php
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
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['clave'] ?? '';
    $password2 = $_POST['clave2'] ?? '';

    // Verificar si las contraseñas coinciden
    if ($password !== $password2) {
      $error = "Las contraseñas no coinciden.";
    } else {
      // Verificar si el correo ya está registrado
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM Usuarios WHERE correo_electronico = :correo");
      $stmt->bindParam(':correo', $email);
      $stmt->execute();
      $correoExistente = $stmt->fetchColumn();

      if ($correoExistente > 0) {
        $error = "El correo electrónico ya está registrado. Por favor, usa otro.";
      } else {
        // Encriptar la contraseña
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Insertar el usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO Usuarios (nombre, correo_electronico, contrasena) VALUES (:nombre, :correo, :clave)");
        $stmt->bindParam(':nombre', $name);
        $stmt->bindParam(':correo', $email);
        $stmt->bindParam(':clave', $passwordHash);

        if ($stmt->execute()) {
          // Registro exitoso, redirigir al login o a index.php
          header("Location: index.php");
          exit();
        } else {
          $error = "Error al registrar el usuario. Inténtalo de nuevo.";
        }
      }
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
  <title>Registro</title>
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
        <form method="POST" action="signup.php">
          <fieldset>
            <legend class="center">Registro</legend>

            <!-- Mostrar mensaje de error -->
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
              </div>
            <?php endif; ?>

            <!-- Campo para el nombre -->
            <label class="sr-only" for="name">Nombre</label>
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-user"></i></div>
              <input type="text" class="form-control" name="name" placeholder="Ingresa tu nombre" required>
            </div>
            <div class="spacing-2"></div>

            <!-- Campo para el correo -->
            <label class="sr-only" for="email">Email</label>
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
              <input type="email" class="form-control" name="email" placeholder="Ingresa tu email" required>
            </div>
            <div class="spacing-2"></div>

            <!-- Campo para la contraseña -->
            <label class="sr-only" for="clave">Contraseña</label>
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-lock"></i></div>
              <input type="password" autocomplete="off" class="form-control" name="clave"
                placeholder="Ingresa tu contraseña" required>
            </div>
            <div class="spacing-2"></div>

            <!-- Campo para confirmar contraseña -->
            <label class="sr-only" for="clave2">Verificar contraseña</label>
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-lock"></i></div>
              <input type="password" autocomplete="off" class="form-control" name="clave2"
                placeholder="Verificar contraseña" required>
            </div>
            <div class="spacing-2"></div>

            <!-- Botón de registro -->
            <button type="submit" class="btn btn-primary btn-block">Registrate</button>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</body>

</html>