<?php 
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_SESSION["curso"])) {
    $curso = $_SESSION["curso"];
} else {
    $curso = "";
}

//   CICLO BÁSICO
if (array_key_exists('1ro-1ra-CB', $_POST)) { 
    $curso = "1ro 1ra CB";
} else if (array_key_exists('1ro-2da-CB', $_POST)) { 
    $curso = "1ro 2da CB";
} else if (array_key_exists('1ro-3ra-CB', $_POST)) { 
    $curso = "1ro 3ra CB";
} else if (array_key_exists('1ro-4ta-CB', $_POST)) { 
    $curso = "1ro 4ta CB";
} else if (array_key_exists('1ro-5ta-CB', $_POST)) { 
    $curso = "1ro 5ta CB";
} else if (array_key_exists('1ro-6ta-CB', $_POST)) { 
    $curso = "1ro 6ta CB";
} else if (array_key_exists('1ro-7ma-CB', $_POST)) { 
    $curso = "1ro 7ma CB";
} else if (array_key_exists('2do-1ra-CB', $_POST)) { 
    $curso = "2do 1ra CB";
} else if (array_key_exists('2do-2da-CB', $_POST)) { 
    $curso = "2do 2da CB";
} else if (array_key_exists('2do-3ra-CB', $_POST)) { 
    $curso = "2do 3ra CB";
} else if (array_key_exists('2do-4ta-CB', $_POST)) { 
    $curso = "2do 4ta CB";
} else if (array_key_exists('2do-5ta-CB', $_POST)) { 
    $curso = "2do 5ta CB";
} else if (array_key_exists('2do-5ta-CB', $_POST)) { 
    $curso = "2do 5ta CB";
}

//    IPP
else if (array_key_exists('1ro-1ra-IPP', $_POST)) { 
    $curso = "1ro 1ra IPP";
} else if (array_key_exists('1ro-2da-IPP', $_POST)) { 
    $curso = "1ro 2da IPP";
} else if (array_key_exists('2do-1ra-IPP', $_POST)) { 
    $curso = "2do 1ra IPP";
} else if (array_key_exists('2do-2da-IPP', $_POST)) { 
    $curso = "2do 2da IPP";
} else if (array_key_exists('3ro-1ra-IPP', $_POST)) { 
    $curso = "3ro 1ra IPP";
} else if (array_key_exists('3ro-2da-IPP', $_POST)) { 
    $curso = "3ro 2da IPP";
} else if (array_key_exists('4to-1ra-IPP', $_POST)) { 
    $curso = "4to 1ra IPP";
} else if (array_key_exists('4to-2da-IPP', $_POST)) { 
    $curso = "4to 2da IPP";
} 

//   GAO
else if (array_key_exists('1ro-1ra-GAO', $_POST)) { 
    $curso = "1ro 1ra GAO";
} else if (array_key_exists('1ro-2da-GAO', $_POST)) { 
    $curso = "1ro 2da GAO";
} else if (array_key_exists('1ro-3ra-GAO', $_POST)) { 
    $curso = "1ro 3ra GAO";
} else if (array_key_exists('1ro-4ta-GAO', $_POST)) { 
    $curso = "1ro 4ta GAO";
} else if (array_key_exists('2do-1ra-GAO', $_POST)) { 
    $curso = "2do 1ra GAO";
} else if (array_key_exists('2do-2da-GAO', $_POST)) { 
    $curso = "2do 2da GAO";
} else if (array_key_exists('2do-3ra-GAO', $_POST)) { 
    $curso = "2do 3ra GAO";
} else if (array_key_exists('2do-4ta-GAO', $_POST)) { 
    $curso = "2do 4ta GAO";
} else if (array_key_exists('3ro-1ra-GAO', $_POST)) { 
    $curso = "3ro 1ra GAO";
} else if (array_key_exists('3ro-2da-GAO', $_POST)) { 
    $curso = "3ro 2da GAO";
} else if (array_key_exists('3ro-3ra-GAO', $_POST)) { 
    $curso = "3ro 3ra GAO";
} else if (array_key_exists('3ro-4ta-GAO', $_POST)) { 
    $curso = "3ro 4ta GAO";
} else if (array_key_exists('4to-1ra-GAO', $_POST)) { 
    $curso = "4to 1ra GAO";
} else if (array_key_exists('4to-2da-GAO', $_POST)) { 
    $curso = "4to 2da GAO";
} else if (array_key_exists('4to-3ra-GAO', $_POST)) { 
    $curso = "4to 3ra GAO";
} else if (array_key_exists('4to-4ta-GAO', $_POST)) { 
    $curso = "4to 4ta GAO";
}

//    TEP
else if (array_key_exists('1ro-1ra-TEP', $_POST)) { 
    $curso = "1ro 1ra TEP";
} else if (array_key_exists('2do-1ra-TEP', $_POST)) { 
    $curso = "2do 1ra TEP";
} else if (array_key_exists('3ro-1ra-TEP', $_POST)) { 
    $curso = "3ro 1ra TEP";
} else if (array_key_exists('4to-1ra-TEP', $_POST)) { 
    $curso = "4to 1ra TEP";
} 

$_SESSION["curso"] = $curso;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Curso</title>
    <link rel="stylesheet" href="css/opciones.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var coll = document.getElementsByClassName("collapsible");
            for (var i = 0; i < coll.length; i++) {
                coll[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    var content = this.nextElementSibling;
                    if (content.style.maxHeight) {
                        content.style.maxHeight = null;
                    } else {
                        content.style.maxHeight = content.scrollHeight + "px";
                    }
                });
            }
        });
    </script>
</head>
<body>
<a href="menu.php"><button class="btn btn-logout">Volver</button></a>
<br>
    <div class="container">
        <h2>Seleccione un curso</h2>
        <form method="POST">
            <div class="cursos">
                <button type="button" class="collapsible">Ciclo Básico</button>
                <div class="content">
                    <input type="submit" name="1ro-1ra-CB" value="1ro 1ra" class="curso">
                    <input type="submit" name="1ro-2da-CB" value="1ro 2da" class="curso">
                    <input type="submit" name="1ro-3ra-CB" value="1ro 3ra" class="curso">
                    <input type="submit" name="1ro-4ta-CB" value="1ro 4ta" class="curso">
                    <input type="submit" name="1ro-5ta-CB" value="1ro 5ta" class="curso">
                    <input type="submit" name="1ro-6ta-CB" value="1ro 6ta" class="curso">
                    <input type="submit" name="1ro-7ma-CB" value="1ro 7ma" class="curso">
                    <input type="submit" name="2do-1ra-CB" value="2do 1ra" class="curso">
                    <input type="submit" name="2do-2da-CB" value="2do 2da" class="curso">
                    <input type="submit" name="2do-3ra-CB" value="2do 3ra" class="curso">
                    <input type="submit" name="2do-4ta-CB" value="2do 4ta" class="curso">
                    <input type="submit" name="2do-5ta-CB" value="2do 5ta" class="curso">
                </div>

                <button type="button" class="collapsible">Ciclo Superior</button>
                <div class="content">
                    <h3>Informática Profesional y Personal</h3>
                    <input type="submit" name="1ro-1ra-IPP" value="1ro 1ra" class="curso">
                    <input type="submit" name="1ro-2da-IPP" value="1ro 2da" class="curso">
                    <input type="submit" name="2do-1ra-IPP" value="2do 1ra" class="curso">
                    <input type="submit" name="2do-2da-IPP" value="2do 2da" class="curso">
                    <input type="submit" name="3ro-1ra-IPP" value="3ro 1ra" class="curso">
                    <input type="submit" name="3ro-2da-IPP" value="3ro 2da" class="curso">
                    <input type="submit" name="4to-1ra-IPP" value="4to 1ra" class="curso">
                    <input type="submit" name="4to-2da-IPP" value="4to 2da" class="curso">
  
                    <h3>Gestión de Administración de Empresas</h3>
                    <input type="submit" name="1ro-1ra-GAO" value="1ro 1ra" class="curso">
                    <input type="submit" name="1ro-2da-GAO" value="1ro 2da" class="curso">
                    <input type="submit" name="1ro-3ra-GAO" value="1ro 3ra" class="curso">
                    <input type="submit" name="1ro-4ta-GAO" value="1ro 4ta" class="curso">
                    <input type="submit" name="2do-1ra-GAO" value="2do 1ra" class="curso">
                    <input type="submit" name="2do-2da-GAO" value="2do 2da" class="curso">
                    <input type="submit" name="2do-3ra-GAO" value="2do 3ra" class="curso">
                    <input type="submit" name="2do-4ta-GAO" value="2do 4ta" class="curso">
                    <input type="submit" name="3ro-1ra-GAO" value="3ro 1ra" class="curso">
                    <input type="submit" name="3ro-2da-GAO" value="3ro 2da" class="curso">
                    <input type="submit" name="3ro-3ra-GAO" value="3ro 3ra" class="curso">
                    <input type="submit" name="3ro-4ta-GAO" value="3ro 4ta" class="curso">
                    <input type="submit" name="4to-1ra-GAO" value="4to 1ra" class="curso">
                    <input type="submit" name="4to-2da-GAO" value="4to 2da" class="curso">
                    <input type="submit" name="4to-3ra-GAO" value="4to 3ra" class="curso">
                    <input type="submit" name="4to-4ta-GAO" value="4to 4ta" class="curso">

                    <h3>Técnico en Programación</h3>
                    <input type="submit" name="1ro-1ra-TEP" value="1ro 1ra" class="curso">
                    <input type="submit" name="2do-1ra-TEP" value="2do 1ra" class="curso">
                    <input type="submit" name="3ro-1ra-TEP" value="3ro 1ra" class="curso">
                    <input type="submit" name="4to-1ra-TEP" value="4to 1ra" class="curso">
                </div>
            </div>
        </form>
    </div>
</body>
</html>
