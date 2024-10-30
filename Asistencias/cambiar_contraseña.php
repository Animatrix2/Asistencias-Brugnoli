<?php
// Iniciar sesión si no está activa
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

// Conexión a la base de datos
$host = "localhost";
$dbname = "registro";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Manejar la solicitud de cambio de contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener las contraseñas del formulario
    $nueva_contrasena = $_POST['contraseña'];
    $verificar_contrasena = $_POST['contraseña_ver'];

    // Verificar que ambas contraseñas coincidan
    if ($nueva_contrasena !== $verificar_contrasena) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        // Encriptar la nueva contraseña
        $contrasena_encriptada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $usuario = $_SESSION['usuario'];
        $sql = "UPDATE `usuarios` SET `contraseña` = '$contrasena_encriptada' WHERE `nombre` = '$usuario' ";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute()) {
            $mensaje = "Contraseña actualizada con éxito.";
        } else {
            $mensaje = "Hubo un error al actualizar la contraseña.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cuentas.css">
    <title>Gestión de Cuentas</title>
</head>
<body>
    <h2>Cambiar contraseña</h2>
    <div class="formulario">
        <!-- Mostrar el mensaje de error o éxito -->
        <?php if (isset($mensaje)): ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <table>
                <tr>
                    <td><label for="contraseña">Nueva contraseña:</label></td>
                    <td><input type="password" name="contraseña" required></td>
                </tr>
                <tr>
                    <td><label for="contraseña_ver">Verificar nueva contraseña:</label></td>
                    <td><input type="password" name="contraseña_ver" required></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Cambiar contraseña"></td>
                </tr>
            </table>
        </form>
    </div>
    <br>
    <a href="index.php"> <button class="btn btn-logout">Volver</button> </a>
    <footer style="text-align: center; padding: 20px;  background-color: #777777; color: white; margin-top: 20px; margin:auto; position: absolute; bottom: 0;">
        <p>Hecho por Almenar, Rodrigo Nicolas (almenar.nicolas@gmail.com) - Alfonsi, Luciano (alfonsiluciano@gmail.com).</p>
        <p>4° 1° CSIPP - PROMOCIÓN 2024</p>
    </footer>
</body>
</html>