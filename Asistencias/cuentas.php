<?php
//Revisar permisos del usuario
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
  }
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

// Verificar si el usuario tiene el permiso "Administrador"
if (strpos($_SESSION['permisos'], 'Administrador') !== false) {
    
} else {
    header("Location: index.php");
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

// Manejo del formulario de creación de cuentas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['crear'])) {
        $nombre = $_POST['nombre'];
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Cifrado de la contraseña

        // Recoger los permisos seleccionados
        $permisos = isset($_POST['cursos']) ? implode(',', $_POST['cursos']) : '';

        // Inserción de la nueva cuenta en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, contraseña, permisos) VALUES (?, ?, ?)");

        // Vinculación de parámetros
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $contraseña);
        $stmt->bindParam(3, $permisos);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            echo "Cuenta creada exitosamente.";
        } else {
            echo "Error al crear la cuenta: ";
            print_r($stmt->errorInfo());
        }
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];

        // Eliminación de la cuenta de la base de datos
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");

        // Vinculación de parámetros
        $stmt->bindParam(1, $id);
        
        if ($stmt->execute()) {
            echo "Cuenta eliminada exitosamente.";
        } else {
            echo "Error al eliminar la cuenta: ";
            print_r($stmt->errorInfo());
        }
    }
}

if (isset($_POST["quitar"])) {
    $id_del = $_POST["id-del"];
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bindParam(1, $id_del, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $msj_quitar = "Usuario eliminado";
    } else {
        $msj_quitar = "Error al eliminar usuario: " . $conn->errorInfo();
    }
}


$permiso = "";

if (isset($_POST["editar-btn"])) {
    $id_editar = $_POST["id"];
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bindParam(1, $id_editar, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $permiso = $usuario["permisos"];
    } else {
        $msj_editar = "No se encontró al usuario.";
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
                <td>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="id-del" value="<?php echo $usuario['id']; ?>">
                        <button type="submit" name="quitar" class="eliminar"></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
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
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 1ra CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 1ra CB"> 1ro 1ra CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 2da CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 2da CB"> 1ro 2da CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 3ra CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 3ra CB"> 1ro 3ra CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 4ta CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 4ta CB"> 1ro 4ta CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 5ta CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 5ta CB"> 1ro 5ta CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 6ta CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 6ta CB"> 1ro 6ta CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 7ma CB", explode(',', $permiso))) echo 'checked'; ?> value="1ro 7ma CB"> 1ro 7ma CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 1ra CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 1ra CB"> 2do 1ra CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 2da CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 2da CB"> 2do 2da CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 3ra CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 3ra CB"> 2do 3ra CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 4ta CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 4ta CB"> 2do 4ta CB<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 5ta CB", explode(',', $permiso))) echo 'checked'; ?> value="2do 5ta CB"> 2do 5ta CB<br>
</div>
</td>

<td>
<div class="IPP">
<br><strong>IPP</strong><br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 1ra IPP", explode(',', $permiso))) echo 'checked'; ?> value="1ro 1ra IPP"> 1ro 1ra IPP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 2da IPP", explode(',', $permiso))) echo 'checked'; ?> value="1ro 2da IPP"> 1ro 2da IPP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 1ra IPP", explode(',', $permiso))) echo 'checked'; ?> value="2do 1ra IPP"> 2do 1ra IPP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 2da IPP", explode(',', $permiso))) echo 'checked'; ?> value="2do 2da IPP"> 2do 2da IPP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 1ra IPP", explode(',', $permiso))) echo 'checked'; ?> value="3ro 1ra IPP"> 3ro 1ra IPP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 2da IPP", explode(',', $permiso))) echo 'checked'; ?> value="3ro 2da IPP"> 3ro 2da IPP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 1ra IPP", explode(',', $permiso))) echo 'checked'; ?> value="4to 1ra IPP"> 4to 1ra IPP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 2da IPP", explode(',', $permiso))) echo 'checked'; ?> value="4to 2da IPP"> 4to 2da IPP<br>
</div>
</td>

<td>
<div class="GAO">
<br><strong>GAO</strong><br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 1ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="1ro 1ra GAO"> 1ro 1ra GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 2da GAO", explode(',', $permiso))) echo 'checked'; ?> value="1ro 2da GAO"> 1ro 2da GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 3ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="1ro 3ra GAO"> 1ro 3ra GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 4ta GAO", explode(',', $permiso))) echo 'checked'; ?> value="1ro 4ta GAO"> 1ro 4ta GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 1ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="2do 1ra GAO"> 2do 1ra GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 2da GAO", explode(',', $permiso))) echo 'checked'; ?> value="2do 2da GAO"> 2do 2da GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 3ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="2do 3ra GAO"> 2do 3ra GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 4ta GAO", explode(',', $permiso))) echo 'checked'; ?> value="2do 4ta GAO"> 2do 4ta GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 1ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="3ro 1ra GAO"> 3ro 1ra GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 2da GAO", explode(',', $permiso))) echo 'checked'; ?> value="3ro 2da GAO"> 3ro 2da GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 3ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="3ro 3ra GAO"> 3ro 3ra GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 4ta GAO", explode(',', $permiso))) echo 'checked'; ?> value="3ro 4ta GAO"> 3ro 4ta GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 1ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="4to 1ra GAO"> 4to 1ra GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 2da GAO", explode(',', $permiso))) echo 'checked'; ?> value="4to 2da GAO"> 4to 2da GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 3ra GAO", explode(',', $permiso))) echo 'checked'; ?> value="4to 3ra GAO"> 4to 3ra GAO<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 4ta GAO", explode(',', $permiso))) echo 'checked'; ?> value="4to 4ta GAO"> 4to 4ta GAO<br>
</div>
</td>

<td>
<div class="TEP"></div>
<br><strong>TEP</strong><br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("1ro 1ra TEP", explode(',', $permiso))) echo 'checked'; ?> value="1ro 1ra TEP"> 1ro 1ra TEP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("2do 1ra TEP", explode(',', $permiso))) echo 'checked'; ?> value="2do 1ra TEP"> 2do 1ra TEP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("3ro 1ra TEP", explode(',', $permiso))) echo 'checked'; ?> value="3ro 1ra TEP"> 3ro 1ra TEP<br>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("4to 1ra TEP", explode(',', $permiso))) echo 'checked'; ?> value="4to 1ra TEP"> 4to 1ra TEP<br>
</div>
</td>
</table>
<input type="checkbox" name="cursos[]" <?php if (isset($permiso) && in_array("Administrador", explode(',', $permiso))) echo 'checked'; ?> value="Administrador"> Administrador<br>

</div>
</div>

            <button class="btn registrar" type="submit" name="crear">Crear Cuenta</button>
        </form>
    </div>


        
        
        <a href="index.php"><button class="btn btn-logout" >Volver</button></a>

    </div>
</div>

</body>
</html>
