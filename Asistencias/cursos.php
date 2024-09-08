<?php
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

// Establecer conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=localhost;dbname=registro", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Conexión fallida: ' . $e->getMessage();
    exit;
}

// Obtener los permisos del usuario desde la sesión
$permisos_usuario = explode(',', $_SESSION['permisos']);

if (isset($_POST["registrar"])) {
    try {
        $pdo->beginTransaction();

        $stmtDelete = $pdo->prepare("DELETE FROM asistencias WHERE fecha = CURRENT_DATE AND alumno_id = :alumno_id");
        foreach ($_POST['estado'] as $alumno_id => $estado) {
            $stmtDelete->execute(['alumno_id' => $alumno_id]);

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
    <link rel="stylesheet" href="css/cursos.css">
    <script>
        function marcarTodos(estado) {
            var radios = document.querySelectorAll('input[type=radio][value="' + estado + '"]');
            radios.forEach(function(radio) {
                radio.checked = true;
            });
        }
    </script>
</head>
<body>
<div>
    <table>
        <tr>
            <th>
                <?php 
                require 'opciones.php';

                // Verificar si el usuario tiene acceso al curso
                if (in_array($curso, $permisos_usuario) OR (in_array("Administrador", $permisos_usuario))) {
                    // Obtener los alumnos del curso permitido
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
                } else {
                    echo "No tiene permiso para ver este curso.";
                    exit;
                }
                ?>
            </th>
        </tr>
        <tr>
            <th>
                <div class="container">
                    <h1>Registro de Asistencia - Curso <?php echo htmlspecialchars($curso); ?></h1>
                    <form method="POST">
                        <div class="table-container">
                            <table class="table">
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
                                                <input type="radio" name="estado[<?php echo $alumno['id']; ?>]" value="asistencia" <?php if ($asistencias[$alumno['id']] === 'asistencia' || $asistencias[$alumno['id']] === null) echo 'checked'; ?>>
                                                Asistencia
                                            </label>
                                            <label>
                                                <input type="radio" name="estado[<?php echo $alumno['id']; ?>]" value="inasistencia" <?php if ($asistencias[$alumno['id']] === 'inasistencia') echo 'checked'; ?>>
                                                Inasistencia
                                            </label>
                                            <label>
                                                <input type="radio" name="estado[<?php echo $alumno['id']; ?>]" value="tardanza" <?php if ($asistencias[$alumno['id']] === 'tardanza') echo 'checked'; ?>>
                                                Tardanza
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <button class="btn" name="registrar" type="submit">Registrar Asistencia</button>
                    </form>

                    <input type="button" class="btn" value="Marcar Asistencia para Todos" onclick="marcarTodos('asistencia')">
                    <input type="button" class="btn" value="Marcar Falta para Todos" onclick="marcarTodos('inasistencia')">

                    <h2>Registro del día de hoy</h2>
                    <table class="table">
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
                                                AND DATE(asi.fecha) = CURDATE()
                                                ORDER BY asi.fecha DESC
                        ");
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
                </div>
            </th>
        </tr>
    </table>
</div>
</body>
</html>
