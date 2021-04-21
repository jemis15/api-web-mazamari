<?php

namespace App\Http\Controllers;

use App\Models\Comision;

class ComisionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // GET v1/comiciones/{year}/{month}
    public function show($year, $month)
    {
        $comision =  Comision::whereYear('fecha', $year)->whereMonth('fecha', $month)->first(['fecha', 'archivo as linkPdf']);
        if (!$comision) {
            $comision = Comision::first(['fecha', 'archivo as linkPdf']);
        }
        if ($comision) {
            $comision->linkPdf = 'https://web.munimazamari.gob.pe/admin/contenido/archivos/comision/' . $comision->linkPdf;
        }

        return response()->json($comision);
    }
}
