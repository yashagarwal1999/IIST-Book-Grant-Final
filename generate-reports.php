<?php

session_start();
include('php/mysqli.php');
// echo 'hey';
require('application/fpdf/fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
$pdf->Cell(95,12,'Title',1);
$pdf->Cell(95,12,'Author',1);
$pdf->Ln();
// max length 18
$pdf->MultiCell(95,30,"AAAAAAAAAA\r\nAAAAAAAAAAA",1,0);
$pdf->Cell(95,30,'BB',1,1);
$pdf->Output();

$connection->close();
?>