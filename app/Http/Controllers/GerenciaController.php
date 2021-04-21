<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;

class GerenciaController extends Controller
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

    // GET v1/gerencias
    public function index()
    {
        $gerencia = Gerencia::all(['idg as id', 'gerencia as nombre']);
        return response()->json(['data' => $gerencia]);
    }
}
