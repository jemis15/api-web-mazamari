<?php

namespace App\Http\Controllers;

use App\Models\Banner;

class BannerController extends Controller
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

    // GET v1/banners/{id}
    public function show($id)
    {
        $banner = Banner::find($id, ['id', 'titulo', 'descripcion', 'image', 'url']);

        return response()->json($banner);
    }
}
