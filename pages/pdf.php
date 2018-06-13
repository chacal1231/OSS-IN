<?php

/*
  An Example PDF Report Using FPDF
  by Matt Doyle

  From "Create Nice-Looking PDFs with PHP and FPDF"
  http://www.elated.com/articles/create-nice-looking-pdfs-php-fpdf/
*/

require( "fpdf/fpdf.php" );
require('../inc/config.php');

//Get variables
$con = mysqli_real_escape_string($link,$_GET['con']);

//Query,
$Query_Con = mysqli_query($link,"SELECT * FROM req WHERE con='$con'");
$Row_Con   = mysqli_fetch_array($Query_Con);
// Begin configuration
$textColour = array( 0, 0, 0 );
$headerColour = array( 0, 0, 0 );
$tableHeaderTopTextColour = array( 255, 255, 255 );
$tableHeaderTopFillColour = array( 100, 152, 179 );
$tableHeaderTopProductTextColour = array( 0, 0, 0 );
$tableHeaderTopProductFillColour = array( 80, 185, 46 );
$tableHeaderLeftTextColour = array( 99, 42, 57 );
$tableHeaderLeftFillColour = array( 184, 207, 229 );
$tableBorderColour = array( 50, 50, 50 );
$tableRowFillColour = array( 213, 170, 170 );
$reportName = "Reporte $Pozo";
$reportNameYPos = 160;
$logoFile = "../backend/panel/images/logo.png";
$logoXPos = 50;
$logoYPos = 108;
$logoWidth = 110;


$pdf = new FPDF( 'P', 'mm', 'A4' );
$pdf->SetAutoPageBreak(true,10);
$pdf->SetTitle("Requisición $con",true);
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );



/**
  Create the page header, main heading, and intro text
**/

$pdf->AddPage();
$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
$pdf->SetFont( 'Arial', 'B', 13 );
$pdf->Image('../backend/panel/images/logo.png' , 10 ,8, 40 , 20,'PNG');
$pdf->Cell( 0, 10, "FORMATO", 0, 0, 'C' );
$pdf->Cell( -190, 20, "HSEQ", 0, 0, 'C' );
$pdf->Cell( 0, 30, "REQUISICION DE MATERIALES Y EQUIPOS", 0, 0, 'C' );
$pdf->SetFont( 'Arial', 'B', 10 );
$pdf -> SetX(155);    // set the cursor at Y position 5
$pdf->Cell( 0, 10, utf8_decode("Versión 005"), 0, 0, 'C' );
$pdf -> SetX(166);    // set the cursor at Y position 5
$pdf->Cell( 0, 20, utf8_decode("Fecha: 2018-02-19"), 0, 0, 'C' );
$pdf -> SetX(158);    // set the cursor at Y position 5
$pdf->Cell( 0, 30, utf8_decode("Página 1 de 1"), 0, 0, 'C' );
$pdf->SetFont( 'Arial', 'B', 15 );
$pdf->Ln( 10 );
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );



/**
  Create the table
**/

$pdf->SetDrawColor( $tableBorderColour[0], $tableBorderColour[1], $tableBorderColour[2] );
$pdf->Ln( 35 );

// Create the table header row
$pdf->SetFont( 'Arial', 'B', 8 );

// "PRODUCT" cell
$pdf->SetTextColor( $tableHeaderTopProductTextColour[0], $tableHeaderTopProductTextColour[1], $tableHeaderTopProductTextColour[2] );
$pdf->SetFillColor( $tableHeaderTopProductFillColour[0], $tableHeaderTopProductFillColour[1], $tableHeaderTopProductFillColour[2] );

//Cells
$pdf->Cell( 45, 8, utf8_decode("Fecha de envío:"), 1, 0, 'C', false );
$pdf->Cell( 30, 8, utf8_decode($Row_Con['fecha_e']), 1, 0, 'C', true );
$pdf -> SetX(140);
$pdf->Cell( 30, 8, utf8_decode("Consecutivo:"), 1, 0, 'C', false );
$pdf->Cell( 30, 8, utf8_decode($con), 1, 0, 'C', true );

$pdf->Ln(8);
$pdf->Cell( 45, 8, "Fecha que se requiere solicitud:", 1, 0, 'C', false );
$pdf->Cell( 30, 8, utf8_decode($Row_Con['fecha_r']), 1, 0, 'C', true );

$pdf->Ln( 20 );

$pdf->SetFont( 'Arial', 'B', 10 );
$pdf->Write(0, 'PROYECTO /CENTRO COSTO: ');
$pdf->SetFont( 'Arial', 'U', 10 );
$pdf -> SetX(70);
$pdf->Write(0, utf8_decode($Row_Con['proy']));

$pdf->Ln( 8 );
$pdf->SetFont( 'Arial', 'B', 10 );
$pdf->Write(0, 'EQUIPO: ');
$pdf->SetFont( 'Arial', 'U', 10 );
$pdf -> SetX(70);
$pdf->Write(0, utf8_decode($Row_Con['equipo']));

$pdf->Ln( 8 );
$pdf->SetFont( 'Arial', 'B', 10 );
$pdf->Write(0, 'NOMBRE DEL SOLICITANTE: ');
$pdf->SetFont( 'Arial', 'U', 10 );
$pdf -> SetX(70);
$pdf->Write(0,utf8_decode($Row_Con['nom']));

$pdf->Ln( 8 );
$pdf->SetFont( 'Arial', 'B', 10 );
$pdf->Write(0, 'CARGO DEL SOLICITANTE: ');
$pdf->SetFont( 'Arial', 'U', 10 );
$pdf -> SetX(70);
$pdf->Write(0, utf8_decode($Row_Con['cargo']));

$pdf->Ln( 10 );
$pdf->SetFont( 'Arial', 'B', 8 );
$pdf->Cell( '10', 8, utf8_decode("ITEM"), 1, 0, 'C', true );
$pdf->Cell( '16', 8, utf8_decode("CANTIDAD"), 1, 0, 'C', true );
$pdf->Cell( '30', 8, utf8_decode("UNIDAD DE MEDIDA"), 1, 0, 'C', true );
$pdf->Cell( '30', 8, utf8_decode("REFERENCIA/TALLA"), 1, 0, 'C', true );
$pdf->Cell( '80', 8, utf8_decode("DESCRIPCIÓN"), 1, 0, 'C', true );
$pdf->Cell( '26', 8, utf8_decode("TIPO DE COMPRA"), 1, 0, 'C', true );
$pdf->Ln(8);
$i=1;
$pdf->SetFont( 'Arial', '', 8 );
$Query = mysqli_query($link,"SELECT * FROM req_productos WHERE con='OSS-REQ-1'");
while($Row = mysqli_fetch_array($Query)){
  $pdf->Cell( '10', 8, $i , 1, 0, 'C', false );
  $pdf->Cell( '16', 8, $Row['cant'], 1, 0, 'C', false );
  $pdf->Cell( '30', 8, $Row['um'], 1, 0, 'C', false );
  $pdf->Cell( '30', 8, $Row['ref'], 1, 0, 'C', false );
  $pdf->Cell( '80', 8, utf8_decode($Row['des']), 1, 0, 'C', false );
  $pdf->Cell( '26', 8, utf8_decode($Row['tp']), 1, 0, 'C', false );
  $pdf->Ln(8);
  $i++;
}

$pdf->Ln(100);
$pdf->SetFont( 'Arial', 'B', 10 );
$pdf->Write(0, utf8_decode('OBSERVACIONES: '));
$pdf->SetFont( 'Arial', 'B', 8 );
$pdf->Ln(2);
$pdf->MultiCell( 190, 5, utf8_decode($Row_Con['obs']), 0);
$pdf->Ln(20);
$pdf->SetFont( 'Arial', 'B', 10 );
$pdf->Write(0, utf8_decode('ELABORADO POR: '));
$pdf->Write(0, utf8_decode('________________________'));
$pdf -> SetX(110);
$pdf->Write(0, utf8_decode('AUTORIZADO POR: '));
$pdf -> SetX(145);
$pdf->Write(0, utf8_decode('________________________'));
$pdf->Output( "Reporte $Nom $date.pdf", "I" );
?>

