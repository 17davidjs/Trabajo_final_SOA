<?php
require_once('../../Librerias/tcpdf.php'); // Asegúrate de que la ruta sea correcta

class MYPDF extends TCPDF {
    // Encabezado personalizado
    public function Header() {
        // Fondo azul oscuro
        $this->SetFillColor(57, 74, 122); // Azul oscuro
        $this->Rect(0, 0, $this->getPageWidth(), 40, 'F');

        // Texto: Nombre y Profesión
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(255, 255, 255); // Blanco
        $this->SetXY(50, 10);
        $this->Cell(0, 10, htmlspecialchars($_POST['nombre'] . ' ' . $_POST['apellidos']), 0, 1, 'L', 0, '', 1);


        // Foto del usuario
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $imgPath = $_FILES['imagen']['tmp_name'];
            $this->Image($imgPath, 10, 5, 30, 30, '', '', '', false, 300, '', false, false, 0, 'M', false, false);
        }
    }

    // Pie de página personalizado
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Crear instancia del PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Gestión de Currículums');
$pdf->SetTitle('Currículum Vitae');
$pdf->SetSubject('Currículum Vitae');
$pdf->SetMargins(10, 50, 10);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// Contenido del PDF en dos columnas
$html = '<table style="width: 100%;">';
$html .= '<tr>';
$html .= '<td style="width: 35%; vertical-align: top;">';

// Sección de contacto
$html .= '<h2 style="color: #394a7a;">Contacto</h2>';
if (!empty($_POST['telefono'])) {
    $html .= '<p><strong>Teléfonos:</strong></p><ul>';
    foreach ($_POST['telefono'] as $telefono) {
        $html .= '<li>' . htmlspecialchars($telefono) . '</li>';
    }
    $html .= '</ul>';
}
if (!empty($_POST['correo_electronico'])) {
    $html .= '<p><strong>Correos Electrónicos:</strong></p><ul>';
    foreach ($_POST['correo_electronico'] as $correo) {
        $html .= '<li>' . htmlspecialchars($correo) . '</li>';
    }
    $html .= '</ul>';
}
if (!empty($_POST['paginaweb'])) {
    $html .= '<p><strong>Páginas WEB:</strong></p><ul>';
    foreach ($_POST['paginaweb'] as $paginaweb) {
        $html .= '<li>' . htmlspecialchars($paginaweb) . '</li>';
    }
    $html .= '</ul>';
}



// Educación
$html .= '<h2 style="color: #394a7a;">Educación</h2>';
if (!empty($_POST['titulo'])) {
    foreach ($_POST['titulo'] as $index => $titulo) {
        $html .= '<p><strong>' . htmlspecialchars($titulo) . '</strong></p>';
        $html .= '<p>' . htmlspecialchars($_POST['institucion'][$index]) . ' (' . htmlspecialchars($_POST['fecha'][$index]) . ')</p>';
    }
}

$html .= '</td>';
$html .= '<td style="width: 65%; vertical-align: top;">';

// Acerca de mí
$html .= '<h2 style="color: #394a7a;">Acerca de mí</h2>';
$html .= '<p>' . nl2br(htmlspecialchars($_POST['info'])) . '</p>';

// Experiencia laboral
$html .= '<h2 style="color: #394a7a;">Experiencia Laboral</h2>';
if (!empty($_POST['puesto'])) {
    foreach ($_POST['puesto'] as $index => $puesto) {
        $html .= '<p><strong>' . htmlspecialchars($puesto) . ' en ' . htmlspecialchars($_POST['empresa'][$index]) . '</strong></p>';
        $html .= '<p>' . htmlspecialchars($_POST['fecha_inicio'][$index]) . ' - ' . htmlspecialchars($_POST['fecha_fin'][$index]) . '</p>';
        $html .= '<p>' . nl2br(htmlspecialchars($_POST['descripcion'][$index])) . '</p>';
    }
}

$html .= '</td>';
$html .= '</tr>';
$html .= '</table>';

// Agregar el contenido al PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y mostrar el PDF
$pdf->Output('curriculum_vitae.pdf', 'I');
?>
