<?php

require 'opciones.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=registro", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Conexión fallida: ' . $e->getMessage();
}

// Obtener los alumnos del curso
$stmt = $pdo->prepare("SELECT id, nombre, apellido FROM alumnos WHERE curso = :curso");
$stmt->execute(['curso' => $curso]);
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener las últimas asistencias registradas para cada alumno
$asistencias = [];
foreach ($alumnos as $alumno) {
    $stmt = $pdo->prepare("SELECT estado FROM asistencias WHERE alumno_id = :alumno_id ORDER BY fecha DESC LIMIT 1");
    $stmt->execute(['alumno_id' => $alumno['id']]);
    $asistencia = $stmt->fetch(PDO::FETCH_ASSOC);
    $asistencias[$alumno['id']] = $asistencia ? $asistencia['estado'] : null;
}

// Si el formulario se envía
if (isset($_POST["registrar"])) {
    try {
        $pdo->beginTransaction();

        // Borrar las entradas anteriores si coinciden en fecha y alumno
        $stmtDelete = $pdo->prepare("DELETE FROM asistencias WHERE fecha = CURRENT_DATE AND alumno_id = :alumno_id");
        foreach ($_POST['estado'] as $alumno_id => $estado) {
            $stmtDelete->execute(['alumno_id' => $alumno_id]);

            // Insertar la nueva asistencia
            $stmtInsert = $pdo->prepare("INSERT INTO asistencias (alumno_id, fecha, estado) VALUES (:alumno_id, CURRENT_DATE, :estado)");
            $stmtInsert->execute(['alumno_id' => $alumno_id, 'estado' => $estado]);
        }
        
        $pdo->commit();
        echo "Asistencia registrada correctamente.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error al registrar la asistencia: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Asistencia</title>
    <script>
        function marcarTodos(estado) {
            var radios = document.querySelectorAll('input[type=radio][value=' + estado + ']');
            radios.forEach(function(radio) {
                radio.checked = true;
            });
        }
    </script>
</head>
<body>
    <h1>Registro de Asistencia - Curso <?php echo htmlspecialchars($curso); ?></h1>
    <form method="POST">
        <table border="1">
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Estado</th>
            </tr>
            <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?php echo htmlspecialchars($alumno['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['apellido']); ?></td>
                    <td>
                        <label>
                            <input type="radio" name="estado[<?php echo $alumno['id']; ?>]" value="asistió" <?php if ($asistencias[$alumno['id']] === 'asistió' || $asistencias[$alumno['id']] === null) echo 'checked'; ?>>
                            Asistió
                        </label>
                        <label>
                            <input type="radio" name="estado[<?php echo $alumno['id']; ?>]" value="faltó" <?php if ($asistencias[$alumno['id']] === 'faltó') echo 'checked'; ?>>
                            Faltó
                        </label>
                        <label>
                            <input type="radio" name="estado[<?php echo $alumno['id']; ?>]" value="tardanza" <?php if ($asistencias[$alumno['id']] === 'tardanza') echo 'checked'; ?>>
                            Tardanza
                        </label>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button name="registrar" type="submit">Registrar Asistencia</button>
    </form>

    <button onclick="marcarTodos('asistió')">Marcar Asistencia para Todos</button>
    <button onclick="marcarTodos('faltó')">Marcar Faltanza para Todos</button>

    <h2>Registro de Faltas y Tardanzas</h2>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Estado</th>
            <th>Fecha</th>
        </tr>
        <?php
        $stmt = $pdo->prepare("SELECT a.nombre, a.apellido, asi.estado, asi.fecha
                               FROM alumnos a
                               JOIN asistencias asi ON a.id = asi.alumno_id
                               WHERE a.curso = :curso
                               ORDER BY asi.fecha DESC");
        $stmt->execute(['curso' => $curso]);
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($registros as $registro): ?>
            <tr>
                <td><?php echo htmlspecialchars($registro['nombre']); ?></td>
                <td><?php echo htmlspecialchars($registro['apellido']); ?></td>
                <td><?php echo htmlspecialchars($registro['estado']); ?></td>
                <td><?php echo htmlspecialchars($registro['fecha']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="index.php"><button>Volver</button></a>
</body>
</html>
