<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inicio de Sesión</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <style>
        /* Asegura que el footer esté al final */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        .main-content {
            flex: 1;
        }
    </style>
</head>
<body>
    <?php
    // Conexión a la base de datos
    $conn = mysqli_connect("localhost", "root", "", "registro") or die("Error en la conexión");

    $mensaje = "";

    if (isset($_POST['boton'])) {
        $boton = $_POST['boton'];
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']);

        if ($boton == "Confirmar") {
            // Consulta SQL para buscar el usuario y sus permisos
            $sql = "SELECT contraseña, permisos FROM usuarios WHERE nombre = '$nombre'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $hashContraseña = $row['contraseña'];
                $permisos = $row['permisos'];

                // Verificar la contraseña
                if (password_verify($contraseña, $hashContraseña)) {
                    // Guardar el nombre de usuario y permisos en la sesión
                    $_SESSION["usuario"] = $nombre;
                    $_SESSION["permisos"] = $permisos;

                    // Redirigir al menú
                    header("Location: index.php");
                    exit();
                } else {
                    $mensaje = "¡Nombre de usuario o contraseña incorrectos!";
                }
            } else {
                $mensaje = "¡Nombre de usuario o contraseña incorrectos!";
            }
        }
    }
    ?>
    <div class="container">
    <div class="titulo">
        <h1>Inicio de Sesión</h1>
    </div>
        <div class="content">
        <form action="" method="post">
            <font size="2" face="Bahnschrift">
                <table align="center">
                    <tr>
                        <td>NOMBRE</td>
                        <td><input type="text" name="nombre" required></td>
                    </tr>
                    <tr>
                        <td>CONTRASEÑA</td>
                        <td><input type="password" name="contraseña" required></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="submit" name="boton" value="Confirmar" class="btn">
                        </td>
                    </tr>
                </table>
            </font>
        </form>
        <p><?php echo $mensaje; ?></p>
        </div>
    </div>
    <footer style="text-align: center; padding: 20px;  background-color: #777777; color: white; margin-top: 20px; margin:auto; position: absolute; bottom: 0;">
        <p>Hecho por Almenar, Rodrigo Nicolas (almenar.nicolas@gmail.com) - Alfonsi, Luciano (alfonsiluciano@gmail.com).</p>
        <p>4° 1° CSIPP - PROMOCIÓN 2024</p>
    </footer>
</body>
</html>