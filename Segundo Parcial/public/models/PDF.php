<?php
require(__DIR__ .'/pdf/fpdf.php');

class PDF extends fpdf
{

    function FancyTable($header, $data)
    {
    // Colores, ancho de línea y fuente en negrita
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Cabecera
    $w = array(35, 35, 35, 25,35);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauración de colores y fuentes
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Datos
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'R',$fill);
        $this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
        $this->Cell($w[3],6,$row[3],'LR',0,'L',$fill);
        $this->Cell($w[4],6,$row[4],'LR',0,'L',$fill);

        $this->Ln();
        $fill = !$fill;
    }
    // Línea de cierre
    $this->Cell(array_sum($w),0,'','T');
    }

    public static function CrearPDF($arrayProd){
        $pdf = new PDF();
        $header = array('Cripto','Nacionalidad', 'Cliente', 'Cantidad','Fecha');

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->AddPage();
        $pdf->Cell(170, 5, 'VENTAS',0,0,'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->FancyTable($header,$arrayProd);
    
        return $pdf;
}
}
