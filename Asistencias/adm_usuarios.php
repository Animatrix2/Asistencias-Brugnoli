<?php
// Conexión a la base de datos
$host = "localhost";
$dbname = "registro";
$username = "root";
$password = "";

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Manejo del formulario de creación de cuentas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['crear'])) {
        $nombre = $_POST['nombre'];
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);

        // Inserción de la nueva cuenta en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, contraseña) VALUES (:nombre, :contraseña)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':contraseña', $contraseña);
        $stmt->execute();

        echo "Cuenta creada exitosamente.";
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];

        // Eliminación de la cuenta de la base de datos
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "Cuenta eliminada exitosamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cuentas</title>
</head>
<body>
    <h2>Crear una nueva cuenta</h2>
    <form method="POST" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" required><br><br>

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
