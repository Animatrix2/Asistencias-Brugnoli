<?php
require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $curso = htmlspecialchars($_POST['curso']);
    $mes_anio = htmlspecialchars($_POST['mes_anio']);
    $asistencias_varones = htmlspecialchars($_POST['asistencias_varones']);
    $asistencias_mujeres = htmlspecialchars($_POST['asistencias_mujeres']);
    $inasistencias_varones = htmlspecialchars($_POST['inasistencias_varones']);
    $inasistencias_mujeres = htmlspecialchars($_POST['inasistencias_mujeres']);
    $tardanzas_varones = htmlspecialchars($_POST['tardanzas_varones']);
    $tardanzas_mujeres = htmlspecialchars($_POST['tardanzas_mujeres']);
    $porcentaje_varones = htmlspecialchars($_POST['porcentaje_varones']);
    $porcentaje_mujeres = htmlspecialchars($_POST['porcentaje_mujeres']);
    $porcentaje_total = htmlspecialchars($_POST['porcentaje_total']);
    $asistencia_media = htmlspecialchars($_POST['asistencia_media']);

    class PDF extends FPDF {
        // Header
        function Header() {
            global $curso, $mes_anio;
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Estadisticas de Asistencias - ' . $curso, 0, 1, 'C');
            $this->Cell(0, 10, $mes_anio, 0, 1, 'C');
            $this->Ln(10);
        }

        // Footer
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
        }

        // Centrar tabla
        function CenterTable($data) {
            // Ancho total de la tabla ajustado a los nuevos anchos de las columnas
            $tableWidth = 180; // Ajustado para reflejar los cambios (50 para Categoría, 40 para las otras columnas)
            // Cálculo para centrar la tabla en la página
            $this->SetX(($this->GetPageWidth() - $tableWidth) / 2);
        
            // Encabezados de la tabla
            $this->Cell(50, 10, 'Categoria', 1, 0, 'C');  // Incrementamos el ancho de esta columna
            $this->Cell(40, 10, 'Varones', 1, 0, 'C');
            $this->Cell(40, 10, 'Mujeres', 1, 0, 'C');
            $this->Cell(50, 10, 'Total', 1, 1, 'C');      // Podemos también ajustar el ancho de otras columnas si es necesario
        
            // Datos de la tabla
            // Datos de la tabla
            foreach ($data as $row) {
                $this->SetX(($this->GetPageWidth() - $tableWidth) / 2); // Centrar cada fila
                
                if ($row['categoria'] == 'Asistencia Media') {
                    // Si es la fila de "Asistencia Media", combinamos las tres columnas en una sola
                    $this->Cell(50, 10, $row['categoria'], 1, 0, 'C'); // Ocupa las 3 columnas de Varones, Mujeres y Total
                    $this->Cell(130, 10, $row['total'], 1, 1, 'C'); // El valor de Asistencia Media en la última columna
                } else {
                    // Para las demás filas, usamos las 4 columnas normales
                    $this->Cell(50, 10, $row['categoria'], 1, 0, 'C');
                    $this->Cell(40, 10, $row['varones'], 1, 0, 'C');
                    $this->Cell(40, 10, $row['mujeres'], 1, 0, 'C');
                    $this->Cell(50, 10, $row['total'], 1, 1, 'C');
                }
}

        }
        
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Datos para la tabla
    $data = [
        ['categoria' => 'Asistencias', 'varones' => $asistencias_varones, 'mujeres' => $asistencias_mujeres, 'total' => $asistencias_varones + $asistencias_mujeres],
        ['categoria' => 'Inasistencias', 'varones' => $inasistencias_varones, 'mujeres' => $inasistencias_mujeres, 'total' => $inasistencias_varones + $inasistencias_mujeres],
        ['categoria' => 'Tardanzas', 'varones' => $tardanzas_varones, 'mujeres' => $tardanzas_mujeres, 'total' => $tardanzas_varones + $tardanzas_mujeres],
        ['categoria' => 'Porcentaje Asistencias', 'varones' => $porcentaje_varones . '%', 'mujeres' => $porcentaje_mujeres . '%', 'total' => $porcentaje_total . '%'],
        ['categoria' => 'Asistencia Media', 'varones' => '', 'mujeres' => '', 'total' => $asistencia_media],
    ];

    // Centrar y mostrar la tabla
    $pdf->CenterTable($data);

    // Salida del PDF
    $pdf->Output('D', 'resumen_asistencias.pdf');
} else {
    echo "No se recibieron datos.";
}
