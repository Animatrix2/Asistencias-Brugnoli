
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cursos.css">
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

    <div class="container">
    <?php
require "opciones.php";
// Iniciar sesión si no está activa
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

// Verificar si el usuario tiene acceso al curso
if (in_array($curso, $permisos_usuario) OR (in_array("Administrador", $permisos_usuario))) {

    // Obtener los alumnos del curso
    $stmt = $pdo->prepare("SELECT id, nombre, apellido FROM alumnos WHERE curso = :curso");
    $stmt->execute(['curso' => $curso]);
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener las últimas asistencias registradas para cada alumno
    $asistencias_tot = [];
    foreach ($alumnos as $alumno) {
        $stmt = $pdo->prepare("SELECT estado FROM asistencias WHERE alumno_id = :alumno_id ORDER BY fecha DESC LIMIT 1");
        $stmt->execute(['alumno_id' => $alumno['id']]);
        $asistencia = $stmt->fetch(PDO::FETCH_ASSOC);
        $asistencias_tot[$alumno['id']] = $asistencia ? $asistencia['estado'] : null;
    }

    // Si se envía el formulario
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

// Obtener el número de días hábiles introducido por el usuario
$diasHabiles = isset($_POST['dias_habiles']) ? (int)$_POST['dias_habiles'] : 1;

$stmt = $pdo->prepare("
    SELECT al.sexo, a.estado, COUNT(*) as total
    FROM asistencias a
    JOIN alumnos al ON a.alumno_id = al.id
    WHERE al.curso = :curso
    AND MONTH(a.fecha) = MONTH(CURRENT_DATE)
    AND YEAR(a.fecha) = YEAR(CURRENT_DATE)
    GROUP BY al.sexo, a.estado
");
$stmt->execute(['curso' => $curso]);
$asistenciaData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$asistencias = [
    'Masculino' => ['asistencia' => 0, 'inasistencia' => 0, 'tardanza' => 0],
    'Femenino' => ['asistencia' => 0, 'inasistencia' => 0, 'tardanza' => 0]
];

foreach ($asistenciaData as $data) {
    $asistencias[$data['sexo']][$data['estado']] = $data['total'];
}

$totalAsistencias = $asistencias['Masculino']['asistencia'] + $asistencias['Femenino']['asistencia'];
$totalInasistencias = $asistencias['Masculino']['inasistencia'] + $asistencias['Masculino']['tardanza'] * 0.25 + $asistencias['Femenino']['inasistencia'] + $asistencias['Femenino']['tardanza'] * 0.25;
$asistenciaMedia = $totalAsistencias / $diasHabiles;

$porcentajeAsistenciasVarones = ($asistencias['Masculino']['asistencia'] / ($diasHabiles * count($alumnos))) * 100;
$porcentajeAsistenciasMujeres = ($asistencias['Femenino']['asistencia'] / ($diasHabiles * count($alumnos))) * 100;
$porcentajeTotalAsistencias = ($totalAsistencias / ($diasHabiles * count($alumnos))) * 100;

} else {
echo "No tiene permiso para ver este curso.";
exit;
}
?>
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
                                    <input type="radio" name="estado[<?php echo $alumno['id']; ?>]" value="asistencia" <?php if ($asistencias_tot[$alumno['id']] === 'asistencia' || $asistencias_tot[$alumno['id']] === null) echo 'checked'; ?>>
                                    asistencia
                                </label>
                                <label>
                                    <input type="radio" name="estado[<?php echo $alumno['id']; ?>]" value="inasistencia" <?php if ($asistencias_tot[$alumno['id']] === 'inasistencia') echo 'checked'; ?>>
                                    inasistencia
                                </label>
                                <label>
                                    <input type="radio" name="estado[<?php echo $alumno['id']; ?>]" value="tardanza" <?php if ($asistencias_tot[$alumno['id']] === 'tardanza') echo 'checked'; ?>>
                                    Tardanza
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <button class="btn" name="registrar" type="submit">Registrar Asistencia</button>
        </form>

        <div class="btn-group">
            <button class="btn" onclick="marcarTodos('asistencia')">Marcar Asistencia para Todos</button>
            <button class="btn" onclick="marcarTodos('inasistencia')">Marcar Falta para Todos</button>
        </div>



        <h2>Resumen de Asistencias del Curso en el Mes Actual</h2>
        <table class="summary-table">
            <tr>
                <th></th>
                <th>Varones</th>
                <th>Mujeres</th>
                <th>Total</th>
            </tr>
            <tr>
                <th>Asistencias</th>
                <td><?php echo htmlspecialchars($asistencias['Masculino']['asistencia']); ?></td>
                <td><?php echo htmlspecialchars($asistencias['Femenino']['asistencia']); ?></td>
                <td><?php echo htmlspecialchars($totalAsistencias); ?></td>
            </tr>
            <tr>
                <th>Inasistencias</th>
                <td><?php echo htmlspecialchars(number_format($asistencias['Masculino']['inasistencia'] + $asistencias['Masculino']['tardanza'] * 0.25, 2)); ?></td>
                <td><?php echo htmlspecialchars(number_format($asistencias['Femenino']['inasistencia']  + $asistencias['Femenino']['tardanza'] * 0.25, 2)); ?></td>
                <td><?php echo htmlspecialchars(number_format($totalInasistencias, 2)); ?></td>
            </tr>
            <tr>
                <td>Porcentaje de Asistencias</td>
                <td><?php echo htmlspecialchars(number_format($porcentajeAsistenciasVarones, 2)); ?>%</td>
                <td><?php echo htmlspecialchars(number_format($porcentajeAsistenciasMujeres, 2)); ?>%</td>
                <td><?php echo htmlspecialchars(number_format($porcentajeTotalAsistencias, 2)); ?>%</td>
            </tr>
            <tr>
                <th>Asistencia Media</th>
                <td colspan="3"><?php echo htmlspecialchars(number_format($asistenciaMedia, 2)); ?></td>
            </tr>
            <tr>
                <th>Días hábiles</th>
                <td <label for="dias_habiles"Días hábiles:</label>
                <form method="POST">
    <input type="number" id="dias_habiles" name="dias_habiles" value="<?php echo htmlspecialchars($diasHabiles); ?>" min="1" required>
    <button class="btn" type="submit" name="actualizar_dias">Actualizar Días Hábiles</button>
</form>
</td>
            </tr>
        </table>

    </div>
</body>
</html>
