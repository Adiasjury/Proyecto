<?php
require __DIR__.'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();
$archivo = 'Documento.txt';
$fp = fopen($archivo,'r');
$texto = fread($fp, filesize($archivo));
$html2pdf->pdf->SetDisplayMode('real');
$html2pdf->writeHTML($texto);
$html2pdf->Output('Registro_de_Entradas_Salidas.pdf');
 ?>
