<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\DocumentoContent;
use App\Models\Normativa;
use Illuminate\Http\Request;

class NormativaController extends Controller
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

    // GET v1/normativas
    public function index(Request $request)
    {
        $descripcion = $request->get('descripcion');
        $year = $request->get('year');
        $tipo = $request->get('tipo');
        $limit = $request->get('limit', 20);
        $page = $request->get('page', 0);
        $separador = $request->get('separador');

        $normatividad = Normativa::query()->join('n_tipon', 'n_tipon.idtn', '=', 'n_normatividad.idtn');
        if ($tipo) {
            if ($separador && $separador !== '') {
                // separamos por el formato que recibimos del cliente
                $partNombre = explode($separador, $tipo);
                // le unimos que quedaria asi "Esto es un titulo de prueba"
                $tipo = implode(" ", $partNombre);
            }
            $normatividad->where('n_tipon.tipo', strtoupper($tipo));
        }
        if ($year) {
            $normatividad->whereDate('n_normatividad.fe_r', '<=', $year . '-12-31')
                ->whereDate('n_normatividad.fe_r', '>=', $year . '-0-1');
        }
        if ($descripcion) {
            $normatividad->where('n_normatividad.descrip', 'LIKE', '%' . $descripcion . '%');
        }
        $normatividad->where('n_normatividad.estado', 1)
            ->orderBy('n_normatividad.fe_r')
            ->skip($limit * $page)
            ->take($limit)
            ->select(
                'n_normatividad.idn as id',
                'n_normatividad.numero',
                'n_normatividad.descrip as descripcion',
                'n_tipon.tipo',
                'n_normatividad.aprobacion',
                'n_normatividad.vigente',
                'n_normatividad.observacion',
                'n_normatividad.fe_r as fecha_registro',
                'n_normatividad.pdf'
            );

        return response()->json($normatividad->get());
    }

    public function getInformaciones(Request $request)
    {
        $groupName = $request->get('group');
        $contents = DocumentoContent::where('groupname', $groupName)->get(['idp as id', 'nombre', 'groupname']);

        $files = [];
        foreach ($contents as $content) {
            $documentos = Documento::with([
                'files' => function ($query) {
                    $query->select('idc as id', 'ids', 'nombre', 'pdf');
                }
            ])
                ->where('idp', $content->id)
                ->where('estado', 1)
                ->get([
                    'ids',
                    'nombre as grupo'
                ]);

            foreach ($documentos as $doc) {
                foreach ($doc->files as $file) {
                    if ($file->pdf && $file->pdf !== '') {
                        $file->pdf = $this->admin_path . '/pdfger/' . $file->pdf;
                    }
                }
            }

            $grupo = [
                'groupname' => $content->nombre,
                'grupos' => $documentos
            ];

            array_push($files, $grupo);
        }

        return response()->json($files);
    }
}
