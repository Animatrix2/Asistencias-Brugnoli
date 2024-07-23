<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos</title>
    <style>
        /* Style the button that is used to open and close the collapsible content */
        .collapsible, .curso {
            background-color: #eee;
            height: auto;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
        }

        /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
        .active, .collapsible:hover {
            background-color: #ccc;
        }

        /* Style the collapsible content. Note: hidden by default */
        .content {
            padding: 0 18px;
            background-color: white;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }

        .content-inner {
            padding: 6px 0; /* Ajustar el relleno para evitar superposiciones */
        }

        .cursos {
            height: auto; /* Ajustar la altura para que se ajuste automáticamente */
        }

    </style>
</head>
<body>

<?php 

session_start();

if (isset($_SESSION["curso"])){
    $curso= $_SESSION["curso"];
} else {
    $curso= "";
}

//   CICLO BÁSICO
if(array_key_exists('1ro-1ra-CB', $_POST)) { 
    $curso="1ro 1ra CB";
} else if(array_key_exists('1ro-2da-CB', $_POST)) { 
    $curso="1ro 2da CB" ;
} else if(array_key_exists('1ro-3ra-CB', $_POST)) { 
    $curso="1ro 3ra CB" ;
} else if(array_key_exists('1ro-4ta-CB', $_POST)) { 
    $curso="1ro 4ta CB" ;
} else if(array_key_exists('1ro-5ta-CB', $_POST)) { 
    $curso="1ro 5ta CB" ;
} else if(array_key_exists('1ro-6ta-CB', $_POST)) { 
    $curso="1ro 6ta CB" ;
} else if(array_key_exists('1ro-7ma-CB', $_POST)) { 
    $curso="1ro 7ma CB" ;
} else if(array_key_exists('2do-1ra-CB', $_POST)) { 
    $curso="2do 1ra CB";
} else if(array_key_exists('2do-2da-CB', $_POST)) { 
    $curso="2do 2da CB" ;
} else if(array_key_exists('2do-3ra-CB', $_POST)) { 
    $curso="2do 3ra CB" ;
} else if(array_key_exists('2do-4ta-CB', $_POST)) { 
    $curso="2do 4ta CB" ;
} else if(array_key_exists('2do-5ta-CB', $_POST)) { 
    $curso="2do 5ta CB" ;
}

//    IPP
else if(array_key_exists('1ro-1ra-IPP', $_POST)) { 
    $curso="1ro 1ra IPP" ;
} else if(array_key_exists('1ro-2da-IPP', $_POST)) { 
    $curso="1ro 2da IPP" ;
} else if(array_key_exists('2do-1ra-IPP', $_POST)) { 
    $curso="2do 1ra IPP";
} else if(array_key_exists('2do-2da-IPP', $_POST)) { 
    $curso="2do 2da IPP" ;
} else if(array_key_exists('3ro-1ra-IPP', $_POST)) { 
    $curso="3ro 1ra IPP";
} else if(array_key_exists('3ro-2da-IPP', $_POST)) { 
    $curso="3ro 2da IPP" ;
} else if(array_key_exists('4to-1ra-IPP', $_POST)) { 
    $curso="4to 1ra IPP";
} else if(array_key_exists('4to-2da-IPP', $_POST)) { 
    $curso="4to 2da IPP" ;
} 

//   GAO
else if(array_key_exists('1ro-1ra-GAO', $_POST)) { 
    $curso="1ro 1ra GAO" ;
} else if(array_key_exists('1ro-2da-GAO', $_POST)) { 
    $curso="1ro 2da GAO" ;
} else if(array_key_exists('1ro-3ra-GAO', $_POST)) { 
    $curso="1ro 3ra GAO" ;
} else if(array_key_exists('1ro-4ta-GAO', $_POST)) { 
    $curso="1ro 4ta GAO" ;
} else if(array_key_exists('2do-1ra-GAO', $_POST)) { 
    $curso="2do 1ra GAO";
} else if(array_key_exists('2do-2da-GAO', $_POST)) { 
    $curso="2do 2da GAO" ;
} else if(array_key_exists('2do-3ra-GAO', $_POST)) { 
    $curso="2do 3ra GAO" ;
} else if(array_key_exists('2do-4ta-GAO', $_POST)) { 
    $curso="2do 4ta GAO" ;
} else if(array_key_exists('3ro-1ra-GAO', $_POST)) { 
    $curso="3ro 1ra GAO";
} else if(array_key_exists('3ro-2da-GAO', $_POST)) { 
    $curso="3ro 2da GAO" ;
} else if(array_key_exists('3ro-3ra-GAO', $_POST)) { 
    $curso="3ro 3ra GAO" ;
} else if(array_key_exists('3ro-4ta-GAO', $_POST)) { 
    $curso="3ro 4ta GAO" ;
} else if(array_key_exists('4to-1ra-GAO', $_POST)) { 
    $curso="4to 1ra GAO";
} else if(array_key_exists('4to-2da-GAO', $_POST)) { 
    $curso="4to 2da GAO" ;
} else if(array_key_exists('4to-3ra-GAO', $_POST)) { 
    $curso="4to 3ra GAO" ;
} else if(array_key_exists('4to-4ta-GAO', $_POST)) { 
    $curso="4to 4ta GAO" ;
}

//    TEP
else if(array_key_exists('1ro-1ra-TEP', $_POST)) { 
    $curso="1ro 1ra TEP" ;
} else if(array_key_exists('2do-1ra-TEP', $_POST)) { 
    $curso="2do 1ra TEP";
} else if(array_key_exists('3ro-1ra-TEP', $_POST)) { 
    $curso="3ro 1ra TEP";
} else if(array_key_exists('4to-1ra-TEP', $_POST)) { 
    $curso="4to 1ra TEP";
} 

$_SESSION["curso"] = $curso;

?>

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

    <script>
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

            // Expandir también los elementos secundarios si existen
            var innerColl = content.getElementsByClassName("collapsible");
            for (var j = 0; j < innerColl.length; j++) {
                innerColl[j].classList.toggle("active");
                var innerContent = innerColl[j].nextElementSibling;
                if (innerContent.style.maxHeight) {
                    innerContent.style.maxHeight = null;
                } else {
                    innerContent.style.maxHeight = innerContent.scrollHeight + "px";
                }
            }
        });
    }
</script>

</body>
</html>
