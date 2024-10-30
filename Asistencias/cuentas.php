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

// Verificar si se ha seleccionado un usuario para editar
$usuarioActual = null;
if (isset($_POST["editar-btn"])) {
    $id = $_POST["id"];
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $usuarioActual = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Convertir permisos en un arreglo para usarlos en los checkboxes
    $permisosActuales = isset($usuarioActual['permisos']) ? explode(',', $usuarioActual['permisos']) : [];
}

// Manejar el formulario de agregar/editar preceptores
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["registrar"]) || isset($_POST["editar"])) {
        $nombre = $_POST["nombre"];
        $permisos = isset($_POST['permisos']) ? implode(',', $_POST['permisos']) : '';
        
        if (isset($_POST["registrar"])) {
            $stmt = $conn->prepare("INSERT INTO `usuarios` (`nombre`, `permisos`) VALUES (?, ?)");
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $permisos);

            if ($stmt->execute()) {
                echo "<script>alert('Usuario agregado exitosamente');</script>";
            } else {
                echo "<script>alert('Error al agregar usuario');</script>";
            }
        }

        if (isset($_POST["editar"])) {
            $id = $_POST["id"];
            
            if (!empty($_POST["nueva_contraseña"])) {
                $nueva_contraseña = password_hash($_POST["nueva_contraseña"], PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE `usuarios` SET `nombre`= ?, `permisos`= ?, `contraseña`= ? WHERE `id` = ?");
                $stmt->bindParam(1, $nombre);
                $stmt->bindParam(2, $permisos);
                $stmt->bindParam(3, $nueva_contraseña);
                $stmt->bindParam(4, $id);
            } else {
                $stmt = $conn->prepare("UPDATE `usuarios` SET `nombre`= ?, `permisos`= ? WHERE `id` = ?");
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
<br>
<a href="index.php"><button class="btn btn-logout">Volver</button></a>
<br>
<div class="container">
    <h2>Lista de Usuarios</h2>
    <div class="table-container">
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
                <td style="display: flex; gap: 5px;">
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

        <h2>Crear una nueva cuenta</h2>
        <div class="formulario">
            <form method="POST" action="">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
                <br>
                
                <label>Permisos:</label><br>
                <input type="checkbox" name="permisos[]" value="Administrador"> Administrador<br>

                <div class="opciones" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <div>
                        <strong>Ciclo Básico</strong><br>
                        <input type="checkbox" name="cursos[]" value="1ro 1ra CB"> 1ro 1ra <br>
                        <input type="checkbox" name="cursos[]" value="1ro 2da CB"> 1ro 2da <br>
                        <input type="checkbox" name="cursos[]" value="1ro 3ra CB"> 1ro 3ra <br>
                        <input type="checkbox" name="cursos[]" value="1ro 4ta CB"> 1ro 4ta <br>
                        <input type="checkbox" name="cursos[]" value="1ro 5ta CB"> 1ro 5ta <br>
                        <input type="checkbox" name="cursos[]" value="1ro 6ta CB"> 1ro 6ta <br>
                        <input type="checkbox" name="cursos[]" value="1ro 7ma CB"> 1ro 7ma <br>
                        <input type="checkbox" name="cursos[]" value="2do 1ra CB"> 2do 1ra <br>
                        <input type="checkbox" name="cursos[]" value="2do 2da CB"> 2do 2da <br>
                        <input type="checkbox" name="cursos[]" value="2do 3ra CB"> 2do 3ra <br>
                        <input type="checkbox" name="cursos[]" value="2do 4ta CB"> 2do 4ta <br>
                        <input type="checkbox" name="cursos[]" value="2do 5ta CB"> 2do 5ta <br>
                    </div>

                    <!-- IPP -->
                    <div>
                        <strong>IPP</strong><br>
                        <input type="checkbox" name="cursos[]" value="1ro 1ra IPP"> 1ro 1ra <br>
                        <input type="checkbox" name="cursos[]" value="1ro 2da IPP"> 1ro 2da <br>
                        <input type="checkbox" name="cursos[]" value="2do 1ra IPP"> 2do 1ra <br>
                        <input type="checkbox" name="cursos[]" value="2do 2da IPP"> 2do 2da <br>
                        <input type="checkbox" name="cursos[]" value="3ro 1ra IPP"> 3ro 1ra <br>
                        <input type="checkbox" name="cursos[]" value="3ro 2da IPP"> 3ro 2da <br>
                        <input type="checkbox" name="cursos[]" value="4to 1ra IPP"> 4to 1ra <br>
                        <input type="checkbox" name="cursos[]" value="4to 2da IPP"> 4to 2da <br>
                    </div>

                    <!-- GAO -->
                    <div>
                        <strong>GAO</strong><br>
                        <input type="checkbox" name="cursos[]" value="1ro 1ra GAO"> 1ro 1ra <br>
                        <input type="checkbox" name="cursos[]" value="1ro 2da GAO"> 1ro 2da <br>
                        <input type="checkbox" name="cursos[]" value="1ro 3ra GAO"> 1ro 3ra <br>
                        <input type="checkbox" name="cursos[]" value="2do 1ra GAO"> 2do 1ra <br>
                        <input type="checkbox" name="cursos[]" value="2do 2da GAO"> 2do 2da <br>
                        <input type="checkbox" name="cursos[]" value="2do 3ra GAO"> 2do 3ra <br>
                        <input type="checkbox" name="cursos[]" value="3ro 1ra GAO"> 3ro 1ra <br>
                        <input type="checkbox" name="cursos[]" value="3ro 2da GAO"> 3ro 2da <br>
                        <input type="checkbox" name="cursos[]" value="3ro 3ra GAO"> 3ro 3ra <br>
                        <input type="checkbox" name="cursos[]" value="4to 1ra GAO"> 4to 1ra <br>
                        <input type="checkbox" name="cursos[]" value="4to 2da GAO"> 4to 2da <br>
                        <input type="checkbox" name="cursos[]" value="4to 3ra GAO"> 4to 3ra <br>
                    </div>

                    <!-- TEP -->
                    <div>
                        <strong>TEP</strong><br>
                        <input type="checkbox" name="cursos[]" value="1ro 1ra TEP"> 1ro 1ra <br>
                        <input type="checkbox" name="cursos[]" value="2do 1ra TEP"> 2do 1ra <br>
                        <input type="checkbox" name="cursos[]" value="3ro 1ra TEP"> 3ro 1ra <br>
                        <input type="checkbox" name="cursos[]" value="4to 1ra TEP"> 4to 1ra <br>
                    </div>
                </div>

                <button class="btn registrar" type="submit" name="registrar">Crear Cuenta</button>
            </form>
        </div>

        <?php if (isset($usuarioActual)): ?>
            <h2>Editar Usuario</h2>
            <div class="formulario">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $usuarioActual['id']; ?>">
                    
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuarioActual['nombre']); ?>" required>
                    <br>
                    
                    <label>Permisos:</label><br>
                    <input type="checkbox" name="permisos[]" value="Administrador" <?php if (in_array("Administrador", $permisosActuales)) echo 'checked'; ?>> Administrador<br>

                    <div class="opciones" style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <!-- Ciclo Básico -->
                        <div>
                            <strong>Ciclo Básico</strong><br>
                            <input type="checkbox" name="cursos[]" value="1ro 1ra CB" <?php if (in_array("1ro 1ra CB", $permisosActuales)) echo 'checked'; ?>> 1ro 1ra CB<br>
                            <input type="checkbox" name="cursos[]" value="1ro 2da CB" <?php if (in_array("1ro 2da CB", $permisosActuales)) echo 'checked'; ?>> 1ro 2da CB<br>
                            <input type="checkbox" name="cursos[]" value="1ro 3ra CB" <?php if (in_array("1ro 3ra CB", $permisosActuales)) echo 'checked'; ?>> 1ro 3ra CB<br>
                            <input type="checkbox" name="cursos[]" value="1ro 4ta CB" <?php if (in_array("1ro 4ta CB", $permisosActuales)) echo 'checked'; ?>> 1ro 4ta CB<br>
                            <input type="checkbox" name="cursos[]" value="1ro 5ta CB" <?php if (in_array("1ro 5ta CB", $permisosActuales)) echo 'checked'; ?>> 1ro 5ta CB<br>
                            <input type="checkbox" name="cursos[]" value="1ro 6ta CB" <?php if (in_array("1ro 6ta CB", $permisosActuales)) echo 'checked'; ?>> 1ro 6ta CB<br>
                            <input type="checkbox" name="cursos[]" value="1ro 7ma CB" <?php if (in_array("1ro 7ma CB", $permisosActuales)) echo 'checked'; ?>> 1ro 7ma CB<br>
                            <input type="checkbox" name="cursos[]" value="2do 1ra CB" <?php if (in_array("2do 1ra CB", $permisosActuales)) echo 'checked'; ?>> 2do 1ra CB<br>
                            <input type="checkbox" name="cursos[]" value="2do 2da CB" <?php if (in_array("2do 2da CB", $permisosActuales)) echo 'checked'; ?>> 2do 2da CB<br>
                            <input type="checkbox" name="cursos[]" value="2do 3ra CB" <?php if (in_array("2do 3ra CB", $permisosActuales)) echo 'checked'; ?>> 2do 3ra CB<br>
                            <input type="checkbox" name="cursos[]" value="2do 4ta CB" <?php if (in_array("2do 4ta CB", $permisosActuales)) echo 'checked'; ?>> 2do 4ta CB<br>
                            <input type="checkbox" name="cursos[]" value="2do 5ta CB" <?php if (in_array("2do 5ta CB", $permisosActuales)) echo 'checked'; ?>> 2do 5ta CB<br>
                        </div>

                        <!-- IPP -->
                        <div>
                            <strong>IPP</strong><br>
                            <input type="checkbox" name="cursos[]" value="1ro 1ra IPP" <?php if (in_array("1ro 1ra IPP", $permisosActuales)) echo 'checked'; ?>> 1ro 1ra IPP<br>
                            <input type="checkbox" name="cursos[]" value="1ro 2da IPP" <?php if (in_array("1ro 2da IPP", $permisosActuales)) echo 'checked'; ?>> 1ro 2da IPP<br>
                            <input type="checkbox" name="cursos[]" value="2do 1ra IPP" <?php if (in_array("2do 1ra IPP", $permisosActuales)) echo 'checked'; ?>> 2do 1ra IPP<br>
                            <input type="checkbox" name="cursos[]" value="2do 2da IPP" <?php if (in_array("2do 2da IPP", $permisosActuales)) echo 'checked'; ?>> 2do 2da IPP<br>
                            <input type="checkbox" name="cursos[]" value="3ro 1ra IPP" <?php if (in_array("3ro 1ra IPP", $permisosActuales)) echo 'checked'; ?>> 3ro 1ra IPP<br>
                            <input type="checkbox" name="cursos[]" value="3ro 2da IPP" <?php if (in_array("3ro 2da IPP", $permisosActuales)) echo 'checked'; ?>> 3ro 2da IPP<br>
                            <input type="checkbox" name="cursos[]" value="4to 1ra IPP" <?php if (in_array("4to 1ra IPP", $permisosActuales)) echo 'checked'; ?>> 4to 1ra IPP<br>
                            <input type="checkbox" name="cursos[]" value="4to 2da IPP" <?php if (in_array("4to 2da IPP", $permisosActuales)) echo 'checked'; ?>> 4to 2da IPP<br>
                        </div>

                        <!-- GAO -->
                        <div>
                            <strong>GAO</strong><br>
                            <input type="checkbox" name="cursos[]" value="1ro 1ra GAO" <?php if (in_array("1ro 1ra GAO", $permisosActuales)) echo 'checked'; ?>> 1ro 1ra GAO<br>
                            <input type="checkbox" name="cursos[]" value="1ro 2da GAO" <?php if (in_array("1ro 2da GAO", $permisosActuales)) echo 'checked'; ?>> 1ro 2da GAO<br>
                            <input type="checkbox" name="cursos[]" value="1ro 3ra GAO" <?php if (in_array("1ro 3ra GAO", $permisosActuales)) echo 'checked'; ?>> 1ro 3ra GAO<br>
                            <input type="checkbox" name="cursos[]" value="2do 1ra GAO" <?php if (in_array("2do 1ra GAO", $permisosActuales)) echo 'checked'; ?>> 2do 1ra GAO<br>
                            <input type="checkbox" name="cursos[]" value="2do 2da GAO" <?php if (in_array("2do 2da GAO", $permisosActuales)) echo 'checked'; ?>> 2do 2da GAO<br>
                            <input type="checkbox" name="cursos[]" value="2do 3ra GAO" <?php if (in_array("2do 3ra GAO", $permisosActuales)) echo 'checked'; ?>> 2do 3ra GAO<br>
                            <input type="checkbox" name="cursos[]" value="3ro 1ra GAO" <?php if (in_array("3ro 1ra GAO", $permisosActuales)) echo 'checked'; ?>> 3ro 1ra GAO<br>
                            <input type="checkbox" name="cursos[]" value="3ro 2da GAO" <?php if (in_array("3ro 2da GAO", $permisosActuales)) echo 'checked'; ?>> 3ro 2da GAO<br>
                            <input type="checkbox" name="cursos[]" value="3ro 3ra GAO" <?php if (in_array("3ro 3ra GAO", $permisosActuales)) echo 'checked'; ?>> 3ro 3ra GAO<br>
                            <input type="checkbox" name="cursos[]" value="4to 1ra GAO" <?php if (in_array("4to 1ra GAO", $permisosActuales)) echo 'checked'; ?>> 4to 1ra GAO<br>
                            <input type="checkbox" name="cursos[]" value="4to 2da GAO" <?php if (in_array("4to 2da GAO", $permisosActuales)) echo 'checked'; ?>> 4to 2da GAO<br>
                            <input type="checkbox" name="cursos[]" value="4to 3ra GAO" <?php if (in_array("4to 3ra GAO", $permisosActuales)) echo 'checked'; ?>> 4to 3ra GAO<br>
                        </div>

                        <!-- TEP -->
                        <div>
                            <strong>TEP</strong><br>
                            <input type="checkbox" name="cursos[]" value="1ro 1ra TEP" <?php if (in_array("1ro 1ra TEP", $permisosActuales)) echo 'checked'; ?>> 1ro 1ra TEP<br>
                            <input type="checkbox" name="cursos[]" value="2do 1ra TEP" <?php if (in_array("2do 1ra TEP", $permisosActuales)) echo 'checked'; ?>> 2do 1ra TEP<br>
                            <input type="checkbox" name="cursos[]" value="3ro 1ra TEP" <?php if (in_array("3ro 1ra TEP", $permisosActuales)) echo 'checked'; ?>> 3ro 1ra TEP<br>
                            <input type="checkbox" name="cursos[]" value="4to 1ra TEP" <?php if (in_array("4to 1ra TEP", $permisosActuales)) echo 'checked'; ?>> 4to 1ra TEP<br>
                        </div>
                    </div>


                    <label for="nueva_contraseña">Nueva Contraseña (opcional):</label>
                    <input type="password" name="nueva_contraseña" placeholder="Ingrese nueva contraseña solo si desea cambiarla">
                    <br>

                    <button class="btn registrar" type="submit" name="editar">Actualizar Usuario</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
<footer style="text-align: center; padding: 20px;  background-color: #777777; color: white; margin-top: 20px;">
    <p>Hecho por Almenar, Rodrigo Nicolas (almenar.nicolas@gmail.com) - Alfonsi, Luciano (alfonsiluciano@gmail.com).</p>
    <p>4° 1° CSIPP - PROMOCIÓN 2024</p>
</footer>
</body>
</html>

