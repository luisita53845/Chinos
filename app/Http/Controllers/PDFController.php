<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Artista;

class PDFController extends Controller
{
    public function index(){

        $pdf = new Fpdf();

        $pdf->AddPage();

        $pdf->SetXY(10,10);

        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetFillColor(36, 117, 172  );

        $pdf->SetFont('Arial', 'B', 12);

        $pdf->Cell(110, 10, "Nombre artista", 1, 0, "C", true);
        $pdf->Cell(50, 10, utf8_decode("Número Albumes"), 1, 1, "C", true);


        //artista y numero de discos por artista
        $artistas = Artista::all();
        $pdf->SetFont('Arial', 'I', 11);
        $pdf->SetFillColor(94, 168, 218 );
        foreach ($artistas as $a) {
            $pdf->Cell(110, 10, substr(utf8_decode($a->Name), 0, 50), 1, 0, "L", true);
            $pdf->Cell(50, 10, $a->albumes()->count(), 1, 1, "C", true);
        }



        $response = response($pdf->Output());

        $response->header("Content-Type" , 'application/pdf');

        return $response;
    }
}
