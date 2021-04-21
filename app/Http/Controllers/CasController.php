<?php

namespace App\Http\Controllers;

use App\Models\Cas;
use Illuminate\Http\Request;

class CasController extends Controller
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

    // GET v1/cas
    public function index(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 0);

        $casQuery = Cas::query()->where('estado', 1);
        if ($year) {
            $casQuery->whereYear('fecha', $year);
        }
        if ($month) {
            $casQuery->whereMonth('fecha', $month);
        }
        $casQuery->skip($limit * $page)
            ->take($limit)
            ->select(
                'idcas as id',
                'num as numero',
                'area',
                'base',
                'anexo',
                'c1 as comunicado1',
                'c2 as comunicado2',
                'c3 as comunicado3',
                'c4 as comunicado4',
                'c5 as comunicado5',
                'evap as evaluacion_psicotecnia',
                'evac as evaluacion_cv',
                'rf as resultado',
                'fecha'
            );
        $cas = $casQuery->get();

        foreach ($cas as $casItem) {
            // pdf's
            $casItem->base = $this->fullPath($casItem->base);
            $casItem->anexo = $this->fullPath($casItem->anexo);
            $casItem->comunicado1 = $this->fullPath($casItem->comunicado1);
            $casItem->comunicado2 = $this->fullPath($casItem->comunicado2);
            $casItem->comunicado3 = $this->fullPath($casItem->comunicado3);
            $casItem->comunicado4 = $this->fullPath($casItem->comunicado4);
            $casItem->comunicado5 = $this->fullPath($casItem->comunicado5);
            $casItem->evaluacion_psicotecnia = $this->fullPath($casItem->evaluacion_psicotecnia);
            $casItem->evaluacion_cv = $this->fullPath($casItem->evaluacion_cv);
            $casItem->resultado = $this->fullPath($casItem->resultado);
        }


        return response()->json($cas);
    }

    public function fullPath($value)
    {
        if ($value) {
            return $this->admin_path . '/cas/' . $value;
        }
        return null;
    }
}
