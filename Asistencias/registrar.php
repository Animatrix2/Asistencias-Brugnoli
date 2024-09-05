<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Preceptores</title>
</head>
<body>
<?php
// Conexión a la base de datos
$conn = mysqli_connect("localhost", "root", "", "preceptores") or die("Error en la conexión: " . mysqli_connect_error());

$mensaje = "";

if (isset($_POST['listo'])) {
    $NomUsu = $_POST['NomUsu'];
    $ConUsu = $_POST['ConUsu'];
    $CorUsu = $_POST['CorUsu'];
    $TelUsu = $_POST['TelUsu'];
    $DirUsu = $_POST['DirUsu'];

    // Recoger los valores de los checkboxes (cursos asignados)
    if (isset($_POST['cursos'])) {
        $cursos = $_POST['cursos'];
        $cursosAsignados = implode(",", $cursos); // Convertir el array de cursos en una cadena separada por comas
    } else {
        $cursosAsignados = ""; // Si no se selecciona ningún curso
    }

    // Verificar si el nombre de usuario ya existe
    $checksql = "SELECT `Nombre_Apellido` FROM `usuarios` WHERE `Nombre_Apellido` = '$NomUsu'";
    $result = mysqli_query($conn, $checksql);

    if (mysqli_num_rows($result) > 0) {
        $mensaje = "Nombre de usuario ya existe";
    } else {
        // Encriptar la contraseña
        $hashContraseña = password_hash($ConUsu, PASSWORD_DEFAULT);

        // Insertar nuevo usuario con cursos asignados
        $textsql = "INSERT INTO `usuarios` (`Nombre_Apellido`, `Contraseña`, `Correo`, `Num_Telefono`, `Direccion`, `cursos_asignados`) VALUES ('$NomUsu', '$hashContraseña', '$CorUsu', '$TelUsu', '$DirUsu', '$cursosAsignados')";
        $consulta = mysqli_query($conn, $textsql);

        if ($consulta) {
            $mensaje = "Usuario agregado correctamente";
        } else {
            $mensaje = "Error al agregar usuario: " . mysqli_error($conn);
        }
    }
}
?>
<button id="Boton-Volver"><a href="menu.php">Volver</a></button>
<center><h1>Registro de Preceptores</h1></center>
<form action="" method="POST">
    <table border="2" align="center">
        <thead>
            <tr>
                <th colspan="2">Ingrese los datos del preceptor</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nombre de Usuario</td>
                <td><input type="text" maxlength="50" name="NomUsu" required></td>
            </tr>
            <tr>
                <td>Contraseña</td>
                <td><input type="password" maxlength="30" name="ConUsu" required></td>
            </tr>
            <tr>
                <td>Correo Electrónico</td>
                <td><input type="email" maxlength="50" name="CorUsu" required></td>
            </tr>
            <tr>
                <td>Número de Teléfono</td>
                <td><input type="text" maxlength="10" name="TelUsu" required></td>
            </tr>
            <tr>
                <td>Dirección</td>
                <td><input type="text" maxlength="50" name="DirUsu" required></td>
            </tr>
            <tr>
                <td>Cursos Asignados</td>
                <td>
                    <input type="checkbox" name="cursos[]" value="1ro A"> 1ro A<br>
                    <input type="checkbox" name="cursos[]" value="1ro B"> 1ro B<br>
                    <input type="checkbox" name="cursos[]" value="2do A"> 2do A<br>
                    <input type="checkbox" name="cursos[]" value="2do B"> 2do B<br>
                    <!-- Puedes agregar más cursos según sea necesario -->
                </td>
            </tr>
            <tr>
                <td><input type="submit" class="boton" name="listo" value="Listo"></td>
                <td><?php echo $mensaje; ?></td>
            </tr>
        </tbody>
    </table>
</form>
</body>
</html>