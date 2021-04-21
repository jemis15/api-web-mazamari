<?php

namespace App\Http\Controllers;

use App\JModels\Vehiculo;
use App\Models\Empresa;
use Illuminate\Http\Request;

class TransporteController extends Controller
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

    // GET v1/empresas
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 0);

        $empresas = Empresa::join('t_personas', 't_personas.idpersonas', '=', 't_empresa.idpersonas')
            ->where('t_empresa.estado_emp', 1)
            ->skip($limit * $page)
            ->take($limit)
            ->get([
                't_empresa.idemp as id',
                't_empresa.npartida as numero_partida',
                't_empresa.nom_emp as nombre_empresa',
                't_empresa.dir_emp as direccion_empresa',
                't_empresa.telf as telefono_empresa',
                't_empresa.ema_emp as email_empresa',
                't_empresa.nresolucion as numero_resolucion',
                't_empresa.fecha',
                't_empresa.ruc',
                't_personas.dni as dni_representante',
                't_personas.ape as apellido_representante',
                't_personas.nom as nombre_representante',
                't_personas.direc as direccion_representante',
            ]);

        return response()->json(['data' => $empresas]);
    }

    public function padronPorEmpresa(Request $request, $empresa_id)
    {
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 0);

        $vehiculos = Vehiculo::join('t_empresa', 't_empresa.idemp', '=', 't_vehiculo.idemp')
            ->join('t_personas', 't_personas.idpersonas', '=', 't_vehiculo.idpersonas')
            ->where('t_empresa.idemp', $empresa_id)
            ->skip($limit * $page)
            ->take($limit)
            ->get([
                't_vehiculo.idv as id',
                't_vehiculo.n_placa as numero_placa',
                't_vehiculo.categoria',
                't_vehiculo.marca',
                't_vehiculo.modelo',
                't_vehiculo.color',
                't_vehiculo.vim',
                't_vehiculo.serie_chasis',
                't_vehiculo.anio_fab as year_fabricacion',
                't_vehiculo.anio_model as year_modelo',
                't_vehiculo.ejes as numero_ejes',
                't_vehiculo.asiento as numero_asientos',
                't_vehiculo.ruedas as numero_ruedas',
                't_vehiculo.carroceria',
                't_vehiculo.zona_regis as zona_registro',
                't_vehiculo.oficina_regis as oficina_registro',
                't_vehiculo.dua_dan',
                't_vehiculo.titulo',
                't_vehiculo.fec_titu',
                't_personas.dni',
                't_personas.ape as epellido',
                't_personas.nom as nombre',
                't_personas.direc as direccion',
                't_personas.dep as departamento',
                't_personas.prov as provincia',
                't_personas.dist as distrito',
                't_personas.celular',
                't_personas.email',
                't_empresa.idemp as empresa_id',
                't_empresa.nom_emp as nombre_empresa',
                't_empresa.telf as telefono_empresa',
                't_empresa.ema_emp as email_empresa',
                't_empresa.nresolucion as numero_resolucion',
            ]);

        return response()->json($vehiculos);
    }
}
