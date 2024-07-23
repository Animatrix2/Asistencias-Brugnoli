<?php
require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido = htmlspecialchars($_POST['apellido']);
    $asistencias = json_decode($_POST['asistencias'], true);

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
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Table Header
    $pdf->Cell(40, 10, 'Fecha', 1);
    $pdf->Cell(40, 10, 'Estado', 1);
    $pdf->Cell(40, 10, 'Justificada', 1);
    $pdf->Ln();

    // Table Body
    foreach ($asistencias as $asistencia) {
        $pdf->Cell(40, 10, htmlspecialchars($asistencia['fecha']), 1);
        $pdf->Cell(40, 10, htmlspecialchars($asistencia['estado']), 1);
        $pdf->Cell(40, 10, $asistencia['estado'] == 'faltó' ? ($asistencia['justificada'] ? 'Sí' : 'No') : '-', 1);
        $pdf->Ln();
    }

    // Output the PDF
    $pdf->Output('D', 'asistencias.pdf');
} else {
    echo "No se recibieron datos.";
}
?>
