<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="styles.css">
    <?php
    session_start();

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit;
    }

    // Verificar si el usuario tiene el permiso "Administrador"
    $esAdministrador = (strpos($_SESSION['permisos'], 'Administrador') !== false);

    echo "<h1>Bienvenido, " . $_SESSION['username'] . "!</h1>";
    echo "<p>Tus permisos son: " . $_SESSION['permisos'] . "</p>";
    ?>
</head>
<body>
    <div class="container">
        <div class="content">
            <a href="cursos.php"><button class="btn">Ver cursos</button></a>
            <?php if ($esAdministrador): ?>
            <a href="agregar_alumnos.php"><button class="btn">Administrar alumnos</button></a>
            <a href="cuentas.php"><button class="btn">Administrar cuentas</button></a>
            <?php endif; ?>
            <a href="logout.php"><button class="btn btn-logout">Cerrar sesión</button></a>
        </div>
    </div>
</body>
</html>
