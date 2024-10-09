<?php
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <table>
        <tr>
            <th>
            <div class="saludo">
                <h1>¡Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario"]); ?>!</h1>
            </div>
            </th>
        </tr>
        <tr>
            <th align="center">    
        <div class="container">
            <div class="content">
                <a href="cursos.php"><button class="btn">Ver cursos</button></a>
                <a href="agregar_alumnos.php"><button class="btn">Administrar alumnos</button></a>
                <?php
                    // Verificar si el usuario tiene el permiso de administrador
                    if (isset($_SESSION["permisos"]) && strpos($_SESSION["permisos"], 'Administrador') !== false) {
                      echo  '<a href="cuentas.php"><button class="btn">Administrar cuentas</button></a>';
                    }
                ?>
                <a href="cambiar_contraseña"><button class="btn">Cambiar contraseña</button></a>
                <a href="logout.php"><button class="btn btn-logout">Cerrar sesión</button></a>
            </div>
        </div>
        </th>
    </tr>
    </table>
</body>
</html>