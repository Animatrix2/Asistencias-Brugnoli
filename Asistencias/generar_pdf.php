<?php
require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido = htmlspecialchars($_POST['apellido']);
    $asistencias = json_decode($_POST['asistencias'], true);

    function quitarTildes($texto) {
        $buscar = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'];
        $reemplazar = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
        return str_replace($buscar, $reemplazar, $texto);
    }

    // Aplicar la función para quitar tildes en nombre y apellido
    $nombre = quitarTildes($nombre);
    $apellido = quitarTildes($apellido);

    class PDF extends FPDF {
        // Header
        function Header() {
            global $nombre, $apellido;
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Faltas y Tardanzas de ' . $nombre . ' ' . $apellido, 0, 1, 'C');
            $this->Ln(10);
        }

        // Footer
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }

        // Centrar la tabla
        function CenterTable($tableWidth) {
            $pageWidth = $this->GetPageWidth(); // Ancho de la página
            $x = ($pageWidth - $tableWidth) / 2; // Calcular el margen izquierdo
            $this->SetX($x);
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Definir el ancho total de la tabla
    $tableWidth = 120; // 40 (ancho de cada columna) * 3 columnas

    // Table Header (centrada)
    $pdf->CenterTable($tableWidth);
    $pdf->Cell(40, 10, 'Fecha', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Estado', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Justificada', 1, 0, 'C');
    $pdf->Ln();



    // Table Body (centrada)
    foreach ($asistencias as $asistencia) {
        // Convertir la fecha a formato DD-MM-AAAA
        $fechaFormateada = date('d-m-Y', strtotime($asistencia['fecha']));

        $pdf->CenterTable($tableWidth);
        $pdf->Cell(40, 10, quitarTildes($fechaFormateada), 1, 0, 'C'); // Fecha en formato DD-MM-AAAA
        $pdf->Cell(40, 10, quitarTildes(htmlspecialchars($asistencia['estado'])), 1, 0, 'C');
        $pdf->Cell(40, 10, $asistencia['estado'] == 'inasistencia' ? ($asistencia['justificada'] ? 'SI' : 'NO') : '-', 1, 0, 'C');
        $pdf->Ln();
    }

    // Output the PDF
    $pdf->Output('D', 'asistencias.pdf');
} else {
    echo "No se recibieron datos.";
}
?>
