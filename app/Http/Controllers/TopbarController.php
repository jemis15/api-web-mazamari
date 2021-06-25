<?php

namespace App\Http\Controllers;

use App\Models\Topbar;
use Illuminate\Http\Request;

class TopbarController extends Controller
{
    // GET v1/topbars
    public function index()
    {
        $topbars = Topbar::all(['id', 'descripcion', 'active']);
        return response()->json(['data' => $topbars]);
    }

    // GET v1/topbars/active
    public function active()
    {
        $topbar = Topbar::where('active', 1)->orderBy('id', 'desc')->first(['id', 'descripcion']);
        return response()->json(['data' => $topbar]);
    }

    // POST v1/topbars/{id}
    public function update(Request $request, $id)
    {
        $topbar = Topbar::find($id)->first(['id', 'descripcion']);
        if (!$topbar) {
            return response('Estas instentando editar un registro que no existe.', 400);
        }

        $topbar->descripcion = $request->post('descripcion');
        $topbar->active = $request->post('active');
        $topbar->save();

        return response()->json(['data' => $topbar]);
    }
}
