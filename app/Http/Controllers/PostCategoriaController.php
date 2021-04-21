<?php

namespace App\Http\Controllers;

use App\Models\PostCategoria;

class PostCategoriaController extends Controller
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

    // GET v1/postcategorias
    public function index()
    {
        $categorias = PostCategoria::all(['id', 'nombre as name']);
        
        return response()->json($categorias);
    }
}
