<?php
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
        $permisos = isset($_POST['cursos']) ? implode(', ', $_POST['cursos']) : '';

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
    <title>Gestión de Cuentas</title>
</head>
<body>
    <h2>Lista de Usuarios</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Contraseña</th>
            <th>Permisos</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
            <td><?php echo htmlspecialchars($usuario['contraseña']); ?></td>
            <td><?php echo htmlspecialchars($usuario['permisos']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

</head>
<body>
    <h2>Crear una nueva cuenta</h2>
    <form method="POST" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" required><br><br>

        <div class="cursos">
        <label>Cursos habilitados:</label><br>

<strong>Ciclo Básico</strong><br>
<input type="checkbox" name="cursos[]" value="1ro 1ra CB"> 1ro 1ra CB<br>
<input type="checkbox" name="cursos[]" value="1ro 2da CB"> 1ro 2da CB<br>
<input type="checkbox" name="cursos[]" value="1ro 3ra CB"> 1ro 3ra CB<br>
<input type="checkbox" name="cursos[]" value="1ro 4ta CB"> 1ro 4ta CB<br>
<input type="checkbox" name="cursos[]" value="1ro 5ta CB"> 1ro 5ta CB<br>
<input type="checkbox" name="cursos[]" value="1ro 6ta CB"> 1ro 6ta CB<br>
<input type="checkbox" name="cursos[]" value="1ro 7ma CB"> 1ro 7ma CB<br>
<input type="checkbox" name="cursos[]" value="2do 1ra CB"> 2do 1ra CB<br>
<input type="checkbox" name="cursos[]" value="2do 2da CB"> 2do 2da CB<br>
<input type="checkbox" name="cursos[]" value="2do 3ra CB"> 2do 3ra CB<br>
<input type="checkbox" name="cursos[]" value="2do 4ta CB"> 2do 4ta CB<br>
<input type="checkbox" name="cursos[]" value="2do 5ta CB"> 2do 5ta CB<br>

<br><strong>IPP</strong><br>
<input type="checkbox" name="cursos[]" value="1ro 1ra IPP"> 1ro 1ra IPP<br>
<input type="checkbox" name="cursos[]" value="1ro 2da IPP"> 1ro 2da IPP<br>
<input type="checkbox" name="cursos[]" value="2do 1ra IPP"> 2do 1ra IPP<br>
<input type="checkbox" name="cursos[]" value="2do 2da IPP"> 2do 2da IPP<br>
<input type="checkbox" name="cursos[]" value="3ro 1ra IPP"> 3ro 1ra IPP<br>
<input type="checkbox" name="cursos[]" value="3ro 2da IPP"> 3ro 2da IPP<br>
<input type="checkbox" name="cursos[]" value="4to 1ra IPP"> 4to 1ra IPP<br>
<input type="checkbox" name="cursos[]" value="4to 2da IPP"> 4to 2da IPP<br>

<br><strong>GAO</strong><br>
<input type="checkbox" name="cursos[]" value="1ro 1ra GAO"> 1ro 1ra GAO<br>
<input type="checkbox" name="cursos[]" value="1ro 2da GAO"> 1ro 2da GAO<br>
<input type="checkbox" name="cursos[]" value="1ro 3ra GAO"> 1ro 3ra GAO<br>
<input type="checkbox" name="cursos[]" value="1ro 4ta GAO"> 1ro 4ta GAO<br>
<input type="checkbox" name="cursos[]" value="2do 1ra GAO"> 2do 1ra GAO<br>
<input type="checkbox" name="cursos[]" value="2do 2da GAO"> 2do 2da GAO<br>
<input type="checkbox" name="cursos[]" value="2do 3ra GAO"> 2do 3ra GAO<br>
<input type="checkbox" name="cursos[]" value="2do 4ta GAO"> 2do 4ta GAO<br>
<input type="checkbox" name="cursos[]" value="3ro 1ra GAO"> 3ro 1ra GAO<br>
<input type="checkbox" name="cursos[]" value="3ro 2da GAO"> 3ro 2da GAO<br>
<input type="checkbox" name="cursos[]" value="3ro 3ra GAO"> 3ro 3ra GAO<br>
<input type="checkbox" name="cursos[]" value="3ro 4ta GAO"> 3ro 4ta GAO<br>
<input type="checkbox" name="cursos[]" value="4to 1ra GAO"> 4to 1ra GAO<br>
<input type="checkbox" name="cursos[]" value="4to 2da GAO"> 4to 2da GAO<br>
<input type="checkbox" name="cursos[]" value="4to 3ra GAO"> 4to 3ra GAO<br>
<input type="checkbox" name="cursos[]" value="4to 4ta GAO"> 4to 4ta GAO<br>

<br><strong>TEP</strong><br>
<input type="checkbox" name="cursos[]" value="1ro 1ra TEP"> 1ro 1ra TEP<br>
<input type="checkbox" name="cursos[]" value="2do 1ra TEP"> 2do 1ra TEP<br>
<input type="checkbox" name="cursos[]" value="3ro 1ra TEP"> 3ro 1ra TEP<br>
<input type="checkbox" name="cursos[]" value="4to 1ra TEP"> 4to 1ra TEP<br>
</div>

</div>
<br>

        <button type="submit" name="crear">Crear Cuenta</button>

    </form>

    <h2>Eliminar una cuenta</h2>
    <form method="POST" action="">
        <label for="id">ID de la cuenta a eliminar:</label>
        <input type="number" name="id" required><br><br>

        <button type="submit" name="eliminar">Eliminar Cuenta</button>
    </form>
</body>
</html>
