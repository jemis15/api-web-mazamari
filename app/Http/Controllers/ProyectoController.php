<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
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

    // GET v1/settings
    public function index(Request $request)
    {
        $gerencia = $request->get('gerencia');
        $estado = $request->get('estado');
        $periodo = $request->get('periodo');
        $page = $request->get('page', 0);
        $limit = $request->get('limit', 10);

        $queryProyectos = Proyecto::query();
        $queryProyectos->join('pr_tipo_proyecto', 'pr_tipo_proyecto.idtp', 'pr_proyecto.idtp')
            ->join('o_gerencia', 'o_gerencia.idg', 'pr_proyecto.idge')
            ->where('o_gerencia.gerencia', $gerencia)
            ->where('pr_proyecto.estado', 1)
            ->skip($limit * $page)
            ->take($limit)
            ->select(
                'pr_proyecto.idpr as id',
                'pr_proyecto.codigo',
                'pr_proyecto.nombre',
                'pr_proyecto.uni_eje as unidad_ejecutora',
                'pr_proyecto.ubi as ubicacion',
                'pr_proyecto.periodo as periodo',
                'pr_proyecto.descripcion',
                'pr_proyecto.obje as objetivo',
                'pr_proyecto.justi as justificacion',
                'pr_proyecto.metas',
                'pr_proyecto.modalidad',
                'pr_proyecto.entidad as entidad_financiera',
                'pr_proyecto.monto as monto_inversion',
                'pr_proyecto.poblacion as poblacion_beneficiaria',
                'pr_proyecto.f_ini as fecha_inicio',
                'pr_proyecto.f_fin as fecha_fin',
                'pr_proyecto.tiempo as tiempo_ejecucion',
                'pr_proyecto.princiac as actividad_principal',
                'pr_proyecto.impac as impacto_ambiental',
                'pr_proyecto.resultado as resultado_esperado',
                'pr_proyecto.pdf',
                'pr_proyecto.avance',
                'o_gerencia.gerencia',
                'pr_tipo_proyecto.tipop as tipo_proyecto',
            );

        // retorna un array
        if ($estado || $periodo) {
            if ($estado === 'proceso') {
                $queryProyectos->where('pr_proyecto.avance', '=', 0);
            }
            if ($estado === 'ejecucion') {
                $queryProyectos->where('pr_proyecto.avance', '>', 0)
                    ->where('pr_proyecto.avance', '<', 100);
            }
            if ($estado === 'finalizado') {
                $queryProyectos->where('pr_proyecto.avance', '=', 100);
            }

            if ($periodo) {
                $queryProyectos->whereYear('pr_proyecto.f_ini', '=', $periodo);
            }

            $proyectos = $queryProyectos->get();
            foreach ($proyectos as $proyecto) {
                $proyecto->pdf = $this->admin_path . '/proyectos/' . $proyecto->pdf;
            }

            return response()->json($proyectos);
        }

        $proyects = [
            $queryProyectos->get(),
            $queryProyectos->where('pr_proyecto.avance', '>', 0)
                ->where('pr_proyecto.avance', '<', 100)
                ->get(),
            $queryProyectos->where('pr_proyecto.avance', '=', 100)->get()
        ];

        foreach ($proyects as $proyectos) {
            foreach ($proyectos as $proyecto) {
                $proyecto->pdf = $this->admin_path . '/proyectos/' . $proyecto->pdf;
            }
        }

        $proyectos = [
            'aprobados' => $proyects[0],
            'ejecucion' => $proyects[1],
            'culminados' => $proyects[2]
        ];
        return response()->json($proyectos);
    }
}
