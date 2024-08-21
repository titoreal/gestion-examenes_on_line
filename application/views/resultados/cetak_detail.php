<?php
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{

    public function Header()
    {
        $image_file = K_PATH_IMAGES . 'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 18);
        $this->SetY(13);
        $this->Cell(0, 15, 'Restultados de Examen', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Examination Results');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$mulai = date('l, d M Y', strtotime($resultados->fecha));
$selesai = date('l, d M Y', strtotime($resultados->terlambat));

// create some HTML content
$html = <<<EOD
<p>
Sistema de Examente en Línea. </br>
Toda la información detallada se proporciona a continuación con el puntaje más alto, más bajo y promedio del estudiante!
</p>
<table>
    <tr>
        <th><b>Nombre de Examen</b></th>
        <td>{$resultados->nombre_resultados}</td>
        <th><b>Curso</b></th>
        <td>{$resultados->nombre_curso}</td> 
    </tr>
    <tr>
        <th><b>Total de planificacion</b></th>
        <td>{$resultados->total_preguntas}</td>
        <th><b>Profesor</b></th>
        <td>{$resultados->nombre_profesor}</td>
    </tr>
    <tr>
        <th><b>Hora</b></th>
        <td>{$resultados->duracion} Minute</td>
        <th><b>Puntuación más baja</b></th>
        <td>{$score->min_score}</td>
    </tr>
    <tr>
        <th><b>Fecha de Inicio</b></th>
        <td>{$mulai}</td>
        <th><b>Puntuación más alta</b></th>
        <td>{$score->max_score}</td>
    </tr>
    <tr>
        <th><b>Fecha de Terminación</b></th>
        <td>{$selesai}</td>
        <th><b>Puntuación media</b></th>
        <td>{$score->avg_score}</td>
    </tr>
</table>
EOD;

$html .= <<<EOD
<br><br><br>
<table border="1" style="border-collapse:collapse">
    <thead>
        <tr align="center">
            <th width="5%">#</th>
            <th width="35%"><b>Nombre</b></th>
            <th width="15%"><b>Clase</b></th>
            <th width="25%"><b>Departamento</b></th>
            <th width="10%"><b>Respuestas Correcta</b></th>
            <th width="10%"><b>Puntaje</b></th>
        </tr>        
    </thead>
    <tbody>
EOD;

$no = 1;
foreach ($hasil as $row) {
    $html .= <<<EOD
    <tr>
        <td align="center" width="5%">{$no}</td>
        <td width="35%">{$row->nombre}</td>
        <td width="15%">{$row->nombre_clase}</td>
        <td width="25%">{$row->nombre_area}</td>
        <td width="10%">{$row->jml}</td>
        <td width="10%">{$row->score}</td>
    </tr>
EOD;
    $no++;
}

$html .= <<<EOD
    </tbody>
</table>
EOD;

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);
// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('resultados_examen.pdf', 'I');
