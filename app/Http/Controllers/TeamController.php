<?php

namespace App\Http\Controllers;

use App\JModels\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->admin_path = 'https://web.munimazamari.gob.pe/admin/contenido/archivos';
    }

    public function index(Request $request, $type)
    {
        $teamQuery = Team::query();

        switch ($type) {
            case 'regidores':
                $teamQuery->join('o_regidor as type', 'type.idp', 'o_personal.idp')
                    ->where('o_cargo.cargo', 'REGIDOR')
                    ->where('o_personal.estado', 1);
                break;
            case 'funcionarios':
                $teamQuery->join('o_funcionario as type', 'type.idp', 'o_personal.idp')
                    ->where('o_cargo.cargo', 'GERENTE')
                    ->where('type.estado', 1);
                break;
            default:
                return response('Tipo de cargo no existe', 404);
        }

        $year = date('Y');
        $teamQuery->join('o_grado', 'o_grado.idgrado', 'o_personal.idgr') //grado
            ->join('o_cargo', 'o_cargo.idoc', 'o_personal.idc') //cargo
            ->join('o_partidop', 'o_partidop.idpp', 'o_personal.idpa') //partido politico
            ->where('type.ini', '<=', $request->get('inicio', $year))
            ->where('type.fin', '>=', $request->get('fin', $year))
            ->select(
                'o_personal.idp as id',
                'o_personal.dni',
                'o_personal.ap as apellido',
                'o_personal.nom as nombre',
                'o_personal.sexo as sexo',
                'o_personal.fnac as fecha_nacimiento',
                'o_personal.celular as telefono',
                'o_personal.email',
                'o_grado.grado as grado_academico',
                'o_cargo.cargo',
                'o_partidop.partido as partido_politico',
                'type.ini as inicio',
                'type.fin',
                'type.hoja as hoja_vida',
                'type.reso as resolucion',
                'type.img as image',
            );

        $team = $teamQuery->get();
        foreach ($team as $item) {
            if ($item->hoja_vida) {
                $item->hoja_vida = $this->admin_path . '/hojavida/' . $item->hoja_vida;
            } else {
                $item->hoja_vida = null;
            }
            if ($item->resolucion) {
                $item->resolucion = $this->admin_path . '/resolucion/' . $item->resolucion;
            } else {
                $item->resolucion = null;
            }

            if ($item->image) {
                $item->image = $this->admin_path . '/img/' . $item->image;
            } else {
                $item->image = "https://ui-avatars.com/api/?name=$item->nombre&size=120&background=random";
            }
        }

        return response()->json([
            'data' => $team
        ]);
    }

    public function alcalde(Request $request)
    {
        $year = date('Y');
        $alcalde = Team::join('o_grado', 'o_grado.idgrado', 'o_personal.idgr') //grado
            ->join('o_cargo', 'o_cargo.idoc', 'o_personal.idc') //cargo
            ->join('o_partidop', 'o_partidop.idpp', 'o_personal.idpa') //partido politico
            ->join('o_alcaldia', 'o_alcaldia.idp', 'o_personal.idp') // data regidor
            ->where('o_cargo.cargo', 'ALCALDE')
            ->where('o_alcaldia.fini', '<=', $request->get('inicio', $year))
            ->where('o_alcaldia.ffin', '>=', $request->get('fin', $year))
            ->where('o_personal.estado', 1)
            ->where('o_alcaldia.estado', 1)
            ->first([
                'o_personal.idp as id',
                'o_personal.dni',
                'o_personal.ap as apellido',
                'o_personal.nom as nombre',
                'o_personal.sexo as sexo',
                'o_personal.fnac as fecha_nacimiento',
                'o_personal.celular as telefono',
                'o_personal.email',
                'o_grado.grado as grado_academico',
                'o_cargo.cargo',
                'o_partidop.partido as partido_politico',
                'o_alcaldia.fini as inicio',
                'o_alcaldia.ffin as fin',
                'o_alcaldia.hojav as hoja_vida',
                'o_alcaldia.resolu as resolucion',
                'o_alcaldia.mensaje',
                'o_alcaldia.img as image',
            ]);

        if ($alcalde) {
            $alcalde->image = $this->admin_path . '/img/' . $alcalde->image;
            $alcalde->resolucion = $this->admin_path . '/resolucion/' . $alcalde->resolucion;
            $alcalde->hoja_vida = $this->admin_path . '/resolucion/' . $alcalde->hoja_vida;
        }

        return response()->json([
            'data' => $alcalde
        ]);
    }
}
