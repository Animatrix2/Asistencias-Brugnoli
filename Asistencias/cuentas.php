<?php
// Revisar permisos del usuario
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

// Verificar si el usuario tiene el permiso "Administrador"
if (strpos($_SESSION['permisos'], 'Administrador') === false) {
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

// Definir $usuarioEditar como null por defecto
$usuarioEditar = null;

// Verificar si se ha presionado el botón de edición
if (isset($_POST["editar-btn"])) {
    $id_editar = $_POST["id"];
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bindParam(1, $id_editar, PDO::PARAM_INT);
    $stmt->execute();
    $usuarioEditar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Manejo del formulario de creación, eliminación y edición de cuentas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['crear'])) {
        $nombre = $_POST['nombre'];
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
        $permisos = isset($_POST['cursos']) ? implode(',', $_POST['cursos']) : '';

        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, contraseña, permisos) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $contraseña);
        $stmt->bindParam(3, $permisos);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario agregado exitosamente');</script>";
        } else {
            echo "<script>alert('Error al agregar usuario');</script>";
        }
    } elseif (isset($_POST['quitar'])) { // Ajuste aquí para verificar el botón "quitar"
        $id_del = $_POST["id-del"];

        // Eliminación de la cuenta de la base de datos
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bindParam(1, $id_del);

        if ($stmt->execute()) {
            echo "<script>alert('Cuenta eliminada exitosamente');</script>";
        } else {
            echo "<script>alert('Error al eliminar la cuenta');</script>";
        }
    } elseif (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $permisos = isset($_POST['cursos']) ? implode(',', $_POST['cursos']) : '';
        
        if (!empty($_POST['contraseña'])) {
            $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, permisos = ?, contraseña = ? WHERE id = ?");
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $permisos);
            $stmt->bindParam(3, $contraseña);
            $stmt->bindParam(4, $id);
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, permisos = ? WHERE id = ?");
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $permisos);
            $stmt->bindParam(3, $id);
        }
    
        if ($stmt->execute()) {
            echo "<script>alert('Usuario actualizado exitosamente');</script>";
        } else {
            echo "<script>alert('Error al actualizar usuario');</script>";
        }
    }
}

// Obtener todos los usuarios de la base de datos
$stmt = $conn->prepare("SELECT * FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <a href="index.php"><button class="btn btn-logout">Volver</button></a>
    <div class="container">
        <h2>Lista de Usuarios</h2>
        <table class="table">
            <tr>
                <th>Nombre</th>
                <th>Permisos</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                <td><?php echo htmlspecialchars($usuario['permisos']); ?></td>
                <td>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="id-del" value="<?php echo $usuario['id']; ?>">
                        <button type="submit" name="quitar" class="eliminar"></button>
                    </form>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                        <button type="submit" name="editar-btn" class="editar"></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Formulario para Crear una nueva cuenta -->
        <h2>Crear una nueva cuenta</h2>
        <div class="formulario">
        <form method="POST" action="">
            <table>
                <tr>
                    <td><label for="nombre">Nombre:</label></td>
                    <td><input type="text" name="nombre" required></td>
                </tr>
                <tr>
                    <td><label for="contraseña">Contraseña:</label></td>
                    <td><input type="password" name="contraseña" required></td>
                </tr>
            </table>
            <div class="cursos">
                <label>Cursos habilitados:</label><br>
                <div class="opciones">
                    <table>
                    <tr>
                    <td>
                    <div class="CB">
                    <strong>Ciclo Básico</strong><br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 1ra CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 1ra CB"> 1ro 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 2da CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 2da CB"> 1ro 2da<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 3ra CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 3ra CB"> 1ro 3ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 4ta CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 4ta CB"> 1ro 4ta<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 5ta CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 5ta CB"> 1ro 5ta<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 6ta CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 6ta CB"> 1ro 6ta<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 7ma CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 7ma CB"> 1ro 7ma<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 1ra CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 1ra CB"> 2do 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 2da CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 2da CB"> 2do 2da<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 3ra CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 3ra CB"> 2do 3ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 4ta CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 4ta CB"> 2do 4ta<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 5ta CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 5ta CB"> 2do 5ta<br>
                    </div>
                    </td>

                    <td>
                    <div class="IPP">
                    <br><strong>IPP</strong><br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 1ra IPP", explode(',', $permiso))) echo 'checked'; ?> value="1ro 1ra IPP"> 1ro 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 2da IPP", explode(',', $permiso))) echo 'checked'; ?> value="1ro 2da IPP"> 1ro 2da<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 1ra IPP", explode(',', $permiso))) echo 'checked'; ?> value="2do 1ra IPP"> 2do 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 2da IPP", explode(',', $permiso))) echo 'checked'; ?> value="2do 2da IPP"> 2do 2da<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 1ra IPP", explode(',', $permiso))) echo 'checked'; ?> value="3ro 1ra IPP"> 3ro 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 2da IPP", explode(',', $permiso))) echo 'checked'; ?> value="3ro 2da IPP"> 3ro 2da<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 1ra IPP", explode(',', $permiso))) echo 'checked'; ?> value="4to 1ra IPP"> 4to 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 2da IPP", explode(',', $permiso))) echo 'checked'; ?> value="4to 2da IPP"> 4to 2da<br>
                    </div>
                    </td>

                    <td>
                    <div class="GAO">
                    <br><strong>GAO</strong><br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 1ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="1ro 1ra GAO"> 1ro 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 2da GAO", explode(',', $permiso))) echo 'checked'; ?> value="1ro 2da GAO"> 1ro 2da<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 3ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="1ro 3ra GAO"> 1ro 3ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 1ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="2do 1ra GAO"> 2do 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 2da GAO", explode(',', $permiso))) echo 'checked'; ?> value="2do 2da GAO"> 2do 2da<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 3ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="2do 3ra GAO"> 2do 3ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 1ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="3ro 1ra GAO"> 3ro 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 2da GAO", explode(',', $permiso))) echo 'checked'; ?> value="3ro 2da GAO"> 3ro 2da<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 3ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="3ro 3ra GAO"> 3ro 3ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 1ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="4to 1ra GAO"> 4to 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 2da GAO", explode(',', $permiso))) echo 'checked'; ?> value="4to 2da GAO"> 4to 2da<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 3ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="4to 3ra GAO"> 4to 3ra<br>
                    </div>
                    </td>

                    <td>
                    <div class="TEP"></div>
                    <br><strong>TEP</strong><br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 1ra TEP", explode(',', $permiso))) echo 'checked'; ?> value="1ro 1ra TEP"> 1ro 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 1ra TEP", explode(',', $permiso))) echo 'checked'; ?> value="2do 1ra TEP"> 2do 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 1ra TEP", explode(',', $permiso))) echo 'checked'; ?> value="3ro 1ra TEP"> 3ro 1ra<br>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 1ra TEP", explode(',', $permiso))) echo 'checked'; ?> value="4to 1ra TEP"> 4to 1ra<br>
                    </div>
                    </td>
                    </table>
                    <input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("Administrador", explode(',', $permiso))) echo 'checked'; ?> value="Administrador"> Administrador<br>
                </div>
            </div>
            <button class="btn registrar" type="submit" name="crear">Crear Cuenta</button>
        </form>
        </div>

        <!-- Formulario para Editar cuenta existente -->
        <?php if ($usuarioEditar): ?>
            <h2>Editar Cuenta</h2>
            <div class="formulario">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $usuarioEditar['id']; ?>">
                    <table>
                        <tr>
                            <td><label for="nombre">Nombre:</label></td>
                            <td><input type="text" name="nombre" value="<?php echo htmlspecialchars($usuarioEditar['nombre']); ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="contraseña">Nueva Contraseña (opcional):</label></td>
                            <td><input type="password" name="contraseña" placeholder="Dejar en blanco si no desea cambiar"></td>
                        </tr>
                    </table>
                    <div class="cursos">
                        <label>Cursos habilitados:</label><br>
                        <div class="opciones">
                            <table>
                                <tr>
                                    <td>
                                        <div class="CB">
                                            <strong>Ciclo Básico</strong><br>
                                            <input type="checkbox" name="cursos[]" value="1ro 1ra CB" <?php if (in_array("1ro 1ra CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 1ra <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 2da CB" <?php if (in_array("1ro 2da CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 2da <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 1ra CB" <?php if (in_array("1ro 3ra CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 3ra <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 2da CB" <?php if (in_array("1ro 4ta CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 4ta <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 1ra CB" <?php if (in_array("1ro 5ta CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 5ta <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 2da CB" <?php if (in_array("1ro 6ta CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 6ta <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 1ra CB" <?php if (in_array("1ro 7ta CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 7ta <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 2da CB" <?php if (in_array("2do 2da CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 2da <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 1ra CB" <?php if (in_array("2do 1ra CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 1ra <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 2da CB" <?php if (in_array("2do 2da CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 2da <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 1ra CB" <?php if (in_array("2do 3ra CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 3ra <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 2da CB" <?php if (in_array("2do 4ta CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 4ta <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 1ra CB" <?php if (in_array("2do 5ta CB", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 5ta <br>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="IPP">
                                        <br><strong>IPP</strong><br>
                                            <input type="checkbox" name="cursos[]" value="1ro 1ra IPP" <?php if (in_array("1ro 1ra IPP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 1ra <br>
                                            <input type="checkbox" name="cursos[]" value="1ro 2da IPP" <?php if (in_array("1ro 2da IPP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 2da <br>
                                            <input type="checkbox" name="cursos[]" value="2do 1ra IPP" <?php if (in_array("2do 1ra IPP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 1ra <br>
                                            <input type="checkbox" name="cursos[]" value="2do 2da IPP" <?php if (in_array("2do 2da IPP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 2da <br>
                                            <input type="checkbox" name="cursos[]" value="3ro 1ra IPP" <?php if (in_array("3ro 1ra IPP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 3ro 1ra <br>
                                            <input type="checkbox" name="cursos[]" value="3ro 2da IPP" <?php if (in_array("3ro 2da IPP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 3ro 2da <br>
                                            <input type="checkbox" name="cursos[]" value="4to 1ra IPP" <?php if (in_array("4to 1ra IPP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 4to 1ra <br>
                                            <input type="checkbox" name="cursos[]" value="4to 2da IPP" <?php if (in_array("4to 2da IPP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 4to 2da <br>
                                        </div>
                                    </td>

                                    <td>
                                    <div class="GAO">
                                    <br><strong>GAO</strong><br>
                                    <input type="checkbox" name="cursos[]" value="1ro 1ra GAO" <?php if (in_array("1ro 1ra GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 1ra <br>
                                    <input type="checkbox" name="cursos[]" value="1ro 2da GAO" <?php if (in_array("1ro 2da GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 2da <br>
                                    <input type="checkbox" name="cursos[]" value="1ro 3ra GAO" <?php if (in_array("1ro 3ra GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 3ra <br>
                                    <input type="checkbox" name="cursos[]" value="2do 1ra GAO" <?php if (in_array("2do 1ra GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 1ra <br>
                                    <input type="checkbox" name="cursos[]" value="2do 2da GAO" <?php if (in_array("2do 2da GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 2da <br>
                                    <input type="checkbox" name="cursos[]" value="2do 3ra GAO" <?php if (in_array("2do 3ra GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 3ra <br>
                                    <input type="checkbox" name="cursos[]" value="3ro 1ra GAO" <?php if (in_array("3ro 1ra GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 3ro 1ra <br>
                                    <input type="checkbox" name="cursos[]" value="3ro 2da GAO" <?php if (in_array("3ro 2da GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 3ro 2da <br>
                                    <input type="checkbox" name="cursos[]" value="3ro 3ra GAO" <?php if (in_array("3ro 3ra GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 3ro 3ra <br>
                                    <input type="checkbox" name="cursos[]" value="4to 1ra GAO" <?php if (in_array("4to 1ra GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 4to 1ra <br>
                                    <input type="checkbox" name="cursos[]" value="4to 2da GAO" <?php if (in_array("4to 2da GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 4to 2da <br>
                                    <input type="checkbox" name="cursos[]" value="4to 3ra GAO" <?php if (in_array("4to 3ra GAO", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 4to 3ra <br>
                                    </div>
                                    </td>

                                    <td>
                                    <div class="TEP"></div>
                                    <br><strong>TEP</strong><br>
                                    <input type="checkbox" name="cursos[]" value="1ro 1ra TEP" <?php if (in_array("1ro 1ra TEP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 1ro 1ra <br>
                                    <input type="checkbox" name="cursos[]" value="2do 1ra TEP" <?php if (in_array("2do 1ra TEP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 2do 1ra <br>
                                    <input type="checkbox" name="cursos[]" value="3ro 1ra TEP" <?php if (in_array("3ro 1ra TEP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 3ro 1ra <br>
                                    <input type="checkbox" name="cursos[]" value="4to 1ra TEP" <?php if (in_array("4to 1ra TEP", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> 4to 1ra <br>
                                    </div>
                                    </td>
                                </tr>
                            </table>
                            <input type="checkbox" name="cursos[]" value="Administrador" <?php if (in_array("Administrador", explode(',', $usuarioEditar['permisos']))) echo 'checked'; ?>> Administrador<br>
                        </div>
                    </div>
                    <button class="btn registrar" type="submit" name="editar">Actualizar Cuenta</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <footer style="text-align: center; padding: 20px;  background-color: #777777; color: white; margin-top: 20px; margin:auto;">
        <p>Hecho por Almenar, Rodrigo Nicolas (almenar.nicolas@gmail.com) - Alfonsi, Luciano (alfonsiluciano@gmail.com).</p>
        <p>4º 1º CSIPP - PROMOCIÓN 2024</p>
    </footer>
</body>
</html>
