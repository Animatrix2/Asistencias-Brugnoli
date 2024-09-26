    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Alumnos</title>
        <link rel="stylesheet" href="css/agregar_alumnos.css">

    </head>
    <body>

    <?php

    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "registro");
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
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

    // Inicializar mensajes
    $msj_registrar = $msj_quitar = $msj_editar = "";

    // Funciones de escape y verificación
    function escapar($dato) {
        return htmlspecialchars($dato, ENT_QUOTES, 'UTF-8');
    }

    function verificarExistencia($conn, $dni) {
        $stmt = $conn->prepare("SELECT * FROM `alumnos` WHERE dni = ?");
        $stmt->bind_param("i", $dni);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0;
    }

    // Variables para editar alumno
    $curso = $nombre = $apellido = $dni = $sexo = "";
    $alumno_ver = null;
    $asistencias = [];

    // Manejar el formulario de agregar/editar
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["registrar"]) || isset($_POST["editar"])) {
            $curso = $_POST["curso"];
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $dni = $_POST["dni"];
            $sexo = $_POST["sexo"];

            if (isset($_POST["registrar"])) {
                if (verificarExistencia($conn, $dni)) {
                    $msj_registrar = "Alumno ya existente";
                    echo "<script>alert('$msj_registrar');</script>";
                } else {
                    $stmt = $conn->prepare("INSERT INTO `alumnos` (`curso`, `nombre`, `apellido`, `dni`, `sexo`) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssis", $curso, $nombre, $apellido, $dni, $sexo);
                    if ($stmt->execute()) {
                        $msj_registrar = "Cuenta agregada";
                        echo "<script>alert('$msj_registrar');</script>";
                    } else {
                        $msj_registrar = "Error al insertar datos: " . $conn->error;
                        echo"<script>alert('$msj_registrar');</script>";
                    }
                }
            }


            if (isset($_POST["editar"])) {
                if (verificarExistencia($conn, $dni)) {
                    $stmt = $conn->prepare("UPDATE `alumnos` SET `curso`= ?, `nombre`= ?, `apellido`= ?, `sexo`= ? WHERE `dni` = ?");
                    $stmt->bind_param("ssssi", $curso, $nombre, $apellido, $sexo, $dni);
                    if ($stmt->execute()) {
                        $msj_editar = "Alumno actualizado";
                        echo "<script>alert('$msj_editar');</script>";
                    } else {
                        $msj_editar = "Error al actualizar datos: " . $conn->error;
                        echo "<script>alert('$msj_editar');</script>";
                    }
                } else {
                    $msj_editar = "Alumno no existente";
                    echo "<script>alert('$msj_editar');</script>";
                }
            }
        }

        // Manejar el formulario de eliminar
        if (isset($_POST["quitar"])) {
            $id_del = $_POST["id-del"];
            $stmt = $conn->prepare("DELETE FROM `alumnos` WHERE id = ?");
            $stmt->bind_param("i", $id_del);
            if ($stmt->execute()) {
                $msj_quitar = "Alumno eliminado";
                echo "<script>alert('$msj_quitar');</script>";
            } else {
                $msj_quitar = "Error al eliminar alumno: " . $conn->error;
                echo "<script>alert('$msj_quitar');</script>";
            }
        }

        if (isset($_POST["editar-btn"])) {
            $id_editar = $_POST["id"];
            // Consultar la información del alumno según el ID
            $stmt = $conn->prepare("SELECT * FROM `alumnos` WHERE id = ?");
            $stmt->bind_param("i", $id_editar);
            $stmt->execute();
            $result_editar = $stmt->get_result();
        
            if ($result_editar->num_rows > 0) {
                $alumno = $result_editar->fetch_assoc();
                // Asignar los datos del alumno a las variables
                $curso = $alumno["curso"];
                $nombre = $alumno["nombre"];
                $apellido = $alumno["apellido"];
                $dni = $alumno["dni"];
                $sexo = $alumno["sexo"];
            } else {
                $msj_editar = "No se encontró al alumno";
                echo "<script>alert('$msj_editar');</script>";
            }
        }


    if (isset($_POST['ver'])) {
        $id_ver = $_POST["id_ver"];
        
        // Buscar el alumno
        $sql = "SELECT * FROM alumnos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_ver);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $alumno_ver = $result->fetch_assoc();
            $alumno_id = $alumno_ver['id'];
            
            // Buscar asistencias del alumno
            $sql = "SELECT * FROM asistencias WHERE alumno_id = ? AND estado != 'asistencia'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $alumno_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $asistencias[] = $row;
            }
        } else {
            echo "Alumno no encontrado.";
        }
    }

    if (isset($_POST['actualizar'])) {
        $justificadas = isset($_POST['justificada']) ? $_POST['justificada'] : [];
        
        foreach ($justificadas as $asistencia_id => $justificada) {
            $sql = "UPDATE asistencias SET justificada = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $justificada = 1; // Solo se actualiza si el checkbox está marcado
            $stmt->bind_param("ii", $justificada, $asistencia_id);
            $stmt->execute();
        }
        
        // Redirigir para evitar resubmisión del formulario
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
        
    }



    // Filtrar resultados
    $filtro_curso = isset($_GET["filtro_curso"]) ? $conn->real_escape_string($_GET["filtro_curso"]) : "";
    $filtro_nombre = isset($_GET["filtro_nombre"]) ? $conn->real_escape_string($_GET["filtro_nombre"]) : "";

    $query = "SELECT * FROM alumnos WHERE 1=1";

    if ($filtro_curso) {
        $query .= " AND curso = '$filtro_curso'";
    }

    if ($filtro_nombre) {
        $query .= " AND (nombre LIKE '%$filtro_nombre%' OR apellido LIKE '%$filtro_nombre%' OR dni LIKE '%$filtro_nombre%')";
    }

    $result = $conn->query($query);

    // Generar tabla de alumnos
    $tabla = '<table border="1" cellspacing="2" cellpadding="2">
    <tr class="header">
    <td>Curso</td>
    <td>Nombre</td>
    <td>Apellido</td>
    <td>DNI</td>
    <td>Sexo</td>
    <td>Acciones</td>
    </tr>';

    if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

    // Contar inasistencias del alumno
    $alumno_id = $row['id'];
    $stmt = $conn->prepare("SELECT COUNT(*) as total_absences FROM asistencias WHERE alumno_id = ? AND estado = 'inasistencia'");
    $stmt->bind_param("i", $alumno_id);
    $stmt->execute();
    $result_absences = $stmt->get_result();
    $absences = $result_absences->fetch_assoc()['total_absences'];

    // Asignar clases de color según inasistencias
    $class = '';
    if ($absences >= 20) {
    $class = 'red-name'; // Rojo para 20 o más inasistencias
    } elseif ($absences >= 15) {
    $class = 'orange-name'; // Naranja para entre 15 y 19 inasistencias
    }

    // Generar la fila de la tabla con la clase CSS
    $tabla .= '<tr class="' . $class . '">
            <td>' . escapar($row["curso"]) . '</td>
            <td>' . escapar($row["nombre"]) . '</td>
            <td>' . escapar($row["apellido"]) . '</td>
            <td>' . escapar($row["dni"]) . '</td>
            <td>' . escapar($row["sexo"]) . '</td>
            <td>
                <form action="" method="post" style="display:inline;">
                    <input type="hidden" name="id_ver" value="' . escapar($row["id"]) . '">
                    <a href="#tabla_ver"><input type="submit" name="ver" class="ver" value=""></a>
                </form>
                <form action="" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="' . escapar($row["id"]) . '">
                    <input type="submit" name="editar-btn" class="editar" value="">
                </form>
                <form action="" method="post" style="display:inline;">
                    <input type="hidden" name="id-del" value="' . escapar($row["id"]) . '">
                    <input type="submit" name="quitar" class="eliminar" value="">
                </form>
            </td>
            </tr>';
    }
    }
    $tabla .= '</table>';
    ?>
            <a href="index.php"> <button class="btn btn-logout">Volver</button> </a>
    <h1>Agregar Alumnos</h1>
    <br>

    <div class="filtros">
        <form method="get" action="">
            <label for="filtro_curso">Curso:</label>
            <select name="filtro_curso" id="filtro_curso">
            <option value="">Todos</option>
            <optgroup label="Ciclo Básico">
            <option value="1ro 1ra CB">1ro 1ra CB</option>
            <option value="1ro 2da CB">1ro 2da CB</option>
            <option value="1ro 3ra CB">1ro 3ra CB</option>
            <option value="1ro 4ta CB">1ro 4ta CB</option>
            <option value="1ro 5ta CB">1ro 5ta CB</option>
            <option value="1ro 6ta CB">1ro 6ta CB</option>
            <option value="1ro 7ma CB">1ro 7ma CB</option>
            <option value="2do 1ra CB">2do 1ra CB</option>
            <option value="2do 2da CB">2do 2da CB</option>
            <option value="2do 3ra CB">2do 3ra CB</option>
            <option value="2do 4ta CB">2do 4ta CB</option>
            <option value="2do 5ta CB">2do 5ta CB</option>
        </optgroup>
        <optgroup label="IPP">
            <option value="1ro 1ra IPP">1ro 1ra IPP</option>
            <option value="1ro 2da IPP">1ro 2da IPP</option>
            <option value="2do 1ra IPP">2do 1ra IPP</option>
            <option value="2do 2da IPP">2do 2da IPP</option>
            <option value="3ro 1ra IPP">3ro 1ra IPP</option>
            <option value="3ro 2da IPP">3ro 2da IPP</option>
            <option value="4to 1ra IPP">4to 1ra IPP</option>
            <option value="4to 2da IPP">4to 2da IPP</option>
        </optgroup>
        <optgroup label="GAO">
            <option value="1ro 1ra GAO">1ro 1ra GAO</option>
            <option value="1ro 2da GAO">1ro 2da GAO</option>
            <option value="1ro 3ra GAO">1ro 3ra GAO</option>
            <option value="1ro 4ta GAO">1ro 4ta GAO</option>
            <option value="2do 1ra GAO">2do 1ra GAO</option>
            <option value="2do 2da GAO">2do 2da GAO</option>
            <option value="2do 3ra GAO">2do 3ra GAO</option>
            <option value="2do 4ta GAO">2do 4ta GAO</option>
            <option value="3ro 1ra GAO">3ro 1ra GAO</option>
            <option value="3ro 2da GAO">3ro 2da GAO</option>
            <option value="3ro 3ra GAO">3ro 3ra GAO</option>
            <option value="3ro 4ta GAO">3ro 4ta GAO</option>
            <option value="4to 1ra GAO">4to 1ra GAO</option>
            <option value="4to 2da GAO">4to 2da GAO</option>
            <option value="4to 3ra GAO">4to 3ra GAO</option>
            <option value="4to 4ta GAO">4to 4ta GAO</option>
        </optgroup>
        <optgroup label="TEP">
            <option value="1ro 1ra TEP">1ro 1ra TEP</option>
            <option value="2do 1ra TEP">2do 1ra TEP</option>
            <option value="3ro 1ra TEP">3ro 1ra TEP</option>
            <option value="4to 1ra TEP">4to 1ra TEP</option>
        </optgroup>
            </select>
            <label for="filtro_nombre">Valor:</label>
            <input type="text" name="filtro_nombre" id="filtro_nombre" placeholder="Nombre, apellido o DNI">
            <input type="submit" value="Filtrar">
        </form>
    </div>


    <div class="acciones">
    <?php echo $tabla; ?>
    </div>
    <br>
    <div class="formularios">
        <form action="" method="post">
            <table class="formulario">
                <h2>Editar Alumno</h2>
                <tr>
                    <td>Curso</td>
                    <td>
                        <select name="curso" required>
                            <optgroup label="Ciclo Básico">
                                <option value="1ro 1ra CB" <?php if(isset($curso) && $curso == "1ro 1ra CB") echo 'selected'; ?>>1ro 1ra CB</option>
                                <option value="1ro 2da CB" <?php if(isset($curso) && $curso == "1ro 2da CB") echo 'selected'; ?>>1ro 2da CB</option>
                                <option value="1ro 3ra CB" <?php if(isset($curso) && $curso == "1ro 3ra CB") echo 'selected'; ?>>1ro 3ra CB</option>
                                <option value="1ro 4ta CB" <?php if(isset($curso) && $curso == "1ro 4ta CB") echo 'selected'; ?>>1ro 4ta CB</option>
                                <option value="1ro 5ta CB" <?php if(isset($curso) && $curso == "1ro 5ta CB") echo 'selected'; ?>>1ro 5ta CB</option>
                                <option value="1ro 6ta CB" <?php if(isset($curso) && $curso == "1ro 6ta CB") echo 'selected'; ?>>1ro 6ta CB</option>
                                <option value="1ro 7ma CB" <?php if(isset($curso) && $curso == "1ro 7ma CB") echo 'selected'; ?>>1ro 7ma CB</option>
                                <option value="2do 1ra CB" <?php if(isset($curso) && $curso == "2do 1ra CB") echo 'selected'; ?>>2do 1ra CB</option>
                                <option value="2do 2da CB" <?php if(isset($curso) && $curso == "2do 2da CB") echo 'selected'; ?>>2do 2da CB</option>
                                <option value="2do 3ra CB" <?php if(isset($curso) && $curso == "2do 3ra CB") echo 'selected'; ?>>2do 3ra CB</option>
                                <option value="2do 4ta CB" <?php if(isset($curso) && $curso == "2do 4ta CB") echo 'selected'; ?>>2do 4ta CB</option>
                                <option value="2do 5ta CB" <?php if(isset($curso) && $curso == "2do 5ta CB") echo 'selected'; ?>>2do 5ta CB</option>
                            </optgroup>
                            <optgroup label="IPP">
                                <option value="1ro 1ra IPP" <?php if(isset($curso) && $curso == "1ro 1ra IPP") echo 'selected'; ?>>1ro 1ra IPP</option>
                                <option value="1ro 2da IPP" <?php if(isset($curso) && $curso == "1ro 2da IPP") echo 'selected'; ?>>1ro 2da IPP</option>
                                <option value="2do 1ra IPP" <?php if(isset($curso) && $curso == "2do 1ra IPP") echo 'selected'; ?>>2do 1ra IPP</option>
                                <option value="2do 2da IPP" <?php if(isset($curso) && $curso == "2do 2da IPP") echo 'selected'; ?>>2do 2da IPP</option>
                                <option value="3ro 1ra IPP" <?php if(isset($curso) && $curso == "3ro 1ra IPP") echo 'selected'; ?>>3ro 1ra IPP</option>
                                <option value="3ro 2da IPP" <?php if(isset($curso) && $curso == "3ro 2da IPP") echo 'selected'; ?>>3ro 2da IPP</option>
                                <option value="4to 1ra IPP" <?php if(isset($curso) && $curso == "4to 1ra IPP") echo 'selected'; ?>>4to 1ra IPP</option>
                                <option value="4to 2da IPP" <?php if(isset($curso) && $curso == "4to 2da IPP") echo 'selected'; ?>>4to 2da IPP</option>
                            </optgroup>
                            <optgroup label="GAO">
                                <option value="1ro 1ra GAO" <?php if(isset($curso) && $curso == "1ro 1ra GAO") echo 'selected'; ?>>1ro 1ra GAO</option>
                                <option value="1ro 2da GAO" <?php if(isset($curso) && $curso == "1ro 2da GAO") echo 'selected'; ?>>1ro 2da GAO</option>
                                <option value="1ro 3ra GAO" <?php if(isset($curso) && $curso == "1ro 3ra GAO") echo 'selected'; ?>>1ro 3ra GAO</option>
                                <option value="1ro 4ta GAO" <?php if(isset($curso) && $curso == "1ro 4ta GAO") echo 'selected'; ?>>1ro 4ta GAO</option>
                                <option value="2do 1ra GAO" <?php if(isset($curso) && $curso == "2do 1ra GAO") echo 'selected'; ?>>2do 1ra GAO</option>
                                <option value="2do 2da GAO" <?php if(isset($curso) && $curso == "2do 2da GAO") echo 'selected'; ?>>2do 2da GAO</option>
                                <option value="2do 3ra GAO" <?php if(isset($curso) && $curso == "2do 3ra GAO") echo 'selected'; ?>>2do 3ra GAO</option>
                                <option value="2do 4ta GAO" <?php if(isset($curso) && $curso == "2do 4ta GAO") echo 'selected'; ?>>2do 4ta GAO</option>
                                <option value="3ro 1ra GAO" <?php if(isset($curso) && $curso == "3ro 1ra GAO") echo 'selected'; ?>>3ro 1ra GAO</option>
                                <option value="3ro 2da GAO" <?php if(isset($curso) && $curso == "3ro 2da GAO") echo 'selected'; ?>>3ro 2da GAO</option>
                                <option value="3ro 3ra GAO" <?php if(isset($curso) && $curso == "3ro 3ra GAO") echo 'selected'; ?>>3ro 3ra GAO</option>
                                <option value="3ro 4ta GAO" <?php if(isset($curso) && $curso == "3ro 4ta GAO") echo 'selected'; ?>>3ro 4ta GAO</option>
                                <option value="4to 1ra GAO" <?php if(isset($curso) && $curso == "4to 1ra GAO") echo 'selected'; ?>>4to 1ra GAO</option>
                                <option value="4to 2da GAO" <?php if(isset($curso) && $curso == "4to 2da GAO") echo 'selected'; ?>>4to 2da GAO</option>
                                <option value="4to 3ra GAO" <?php if(isset($curso) && $curso == "4to 3ra GAO") echo 'selected'; ?>>4to 3ra GAO</option>
                                <option value="4to 4ta GAO" <?php if(isset($curso) && $curso == "4to 4ta GAO") echo 'selected'; ?>>4to 4ta GAO</option>
                            </optgroup>
                            <optgroup label="TEP">
                                <option value="1ro 1ra TEP" <?php if(isset($curso) && $curso == "1ro 1ra TEP") echo 'selected'; ?>>1ro 1ra TEP</option>
                                <option value="2do 1ra TEP" <?php if(isset($curso) && $curso == "2do 1ra TEP") echo 'selected'; ?>>2do 1ra TEP</option>
                                <option value="3ro 1ra TEP" <?php if(isset($curso) && $curso == "3ro 1ra TEP") echo 'selected'; ?>>3ro 1ra TEP</option>
                                <option value="4to 1ra TEP" <?php if(isset($curso) && $curso == "4to 1ra TEP") echo 'selected'; ?>>4to 1ra TEP</option>
                            </optgroup>
                            
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Nombre</td>
                    <td><input type="text" maxlength="20" name="nombre" value="<?php if(isset($nombre)) echo escapar($nombre); ?>" required></td>
                </tr>
                <tr>
                    <td>Apellido</td>
                    <td><input type="text" maxlength="20" name="apellido" value="<?php if(isset($apellido)) echo escapar($apellido); ?>" required></td>
                </tr>
                <tr>
                    <td>DNI</td>
                    <td><input type="text" maxlength="8" name="dni" value="<?php if(isset($dni)) echo escapar($dni); ?>" required></td>
                </tr>
                <tr>
                    <td>Sexo</td>
                    <td>
                        <select name="sexo" required>
                            <option value="Masculino" <?php if(isset($sexo) && $sexo == "Masculino") echo 'selected'; ?>>Masculino</option>
                            <option value="Femenino" <?php if(isset($sexo) && $sexo == "Femenino") echo 'selected'; ?>>Femenino</option>
                    </td>
                </tr>
            </table>
            <div class="mensaje">
            <?php echo escapar($msj_registrar); ?>
            </div>
            <div class="mensaje">
            <?php echo escapar($msj_editar); ?>
            </div>
            <div class="botones">
            <input class="btn registrar"  type="submit" name="registrar" value="Registrar">
            <input class="btn editar2" type="submit" name="editar" value="Editar">
            
            </div>

            
        </form>
    </div>

    <?php if ($alumno_ver): ?>
    <div class="container">
        <h2>Faltas y Tardanzas de <?php echo htmlspecialchars($alumno_ver['nombre'] . ' ' . $alumno_ver['apellido']); ?></h2>

        <form method="post" action="">
            <table id="tabla_ver" class="resultados_alumno">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Justificada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $faltastotales = 0;
                    $tardanzas = 0;
                    $faltas = 0;
                    foreach ($asistencias as $asistencia): 
                        if ($asistencia['estado'] == 'inasistencia') {
                            $faltastotales++;
                            $faltas++;
                        } elseif ($asistencia['estado'] == 'tardanza') {
                            $faltastotales += 0.25;
                            $tardanzas++;
                        }
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($asistencia['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($asistencia['estado']); ?></td>
                        <td>
                            <?php if ($asistencia['estado'] == 'inasistencia'): ?>
                            <input type="checkbox" name="justificada[<?php echo $asistencia['id']; ?>]" <?php echo $asistencia['justificada'] ? 'checked' : ''; ?>>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p>Total de inasistencias: <?php echo $faltastotales; ?></p>
            <p>Tardanzas: <?php echo $tardanzas; ?></p>
            <p>Faltas: <?php echo $faltas; ?></p>

            <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($alumno_ver['nombre']); ?>">
            <input type="hidden" name="apellido" value="<?php echo htmlspecialchars($alumno_ver['apellido']); ?>">

            <div class="botones">
                <input type="submit" name="actualizar" value="Guardar" class="btn editar2">
            </div>
        </form>

        <form method="post" action="generar_pdf.php">
            <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($alumno_ver['nombre']); ?>">
            <input type="hidden" name="apellido" value="<?php echo htmlspecialchars($alumno_ver['apellido']); ?>">
            <input type="hidden" name="asistencias" value='<?php echo json_encode($asistencias); ?>'>
            
            <div class="botones">
                <input type="submit" name="descargar" value="Descargar en PDF" class="btn registrar">
            </div>
        </form>
    </div>
    <?php endif; ?>

    <?php
    $conn->close();
    ?>



    </body>
    </html>
