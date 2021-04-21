<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use Illuminate\Http\Request;

class LibroReclamacionController extends Controller
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

    // POST v1/libroreclamaciones/create
    public function create(Request $request)
    {
        $libroReclamaciones = new Reclamo();
        $libroReclamaciones->tipoIncidencia = $request->post('tipoIncidencia');
        $libroReclamaciones->tipoDocumento = $request->post('tipoDocumento');
        $libroReclamaciones->numeroDocumento = $request->post('muneroDocumento');
        $libroReclamaciones->nombres = $request->post('nombres');
        $libroReclamaciones->apellidos = $request->post('apellidos');
        $libroReclamaciones->direccion = $request->post('direccion');
        $libroReclamaciones->provincia = $request->post('provincia');
        $libroReclamaciones->distrito = $request->post('distrito');
        $libroReclamaciones->email = $request->post('email');
        $libroReclamaciones->celular = $request->post('celular');
        $libroReclamaciones->telefono = $request->post('telefono');
        $libroReclamaciones->areaReclamo = $request->post('areaReclamo');
        $libroReclamaciones->tematica = $request->post('tematica');
        $libroReclamaciones->descripcionIncidencia = $request->post('descripcionIncidencia');
        $libroReclamaciones->libroId2 = uniqid();
        $libroReclamaciones->save();

        return response()->json($libroReclamaciones);
    }
}
