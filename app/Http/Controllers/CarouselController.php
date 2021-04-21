<?php

namespace App\Http\Controllers;

use App\Models\Carousel;

class CarouselController extends Controller
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

    // GET v1/carouseles
    public function index()
    {
        $carouseles = Carousel::get(['id', 'titulo', 'descripcion', 'image', 'url']);
        return response()->json(['data' => $carouseles]);
    }
}
