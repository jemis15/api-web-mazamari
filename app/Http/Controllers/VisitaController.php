<?php

namespace App\Http\Controllers;

use App\Models\RegistroIp;
use App\Models\Visita;
use Illuminate\Http\Request;

class VisitaController extends Controller
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
        session_start();
        $sessionId = session_id();
        $remoteIp = $request->server('REMOTE_ADDR');

        $visitas = Visita::first(['id', 'totalvisitas']);

        // valor de la cookie user_session del navegador cliente
        $user_session = $request->cookie('user_session');
        // verificamos si el usuario es nuevo
        if ($sessionId !== $user_session) {
            // guardamos la nueva sesion en el navegador del cliente
            setcookie("user_session", $sessionId, time() + (60 * 60 * 24), "/", ""); //24 Horas

            // incrementamos la visita
            $visitas->increment('totalvisitas');
            // registramos el ip del usuario
            $registro = new RegistroIp();
            $registro->ip = $remoteIp;
            $registro->save();
        }

        $hoy = date('Y-m-d');
        $diaIniciaSemana = date('Y-m-d', strtotime($hoy . '- ' . date('w') . ' days'));

        $consultaDia = RegistroIp::whereDate('created_at', $hoy)->count();
        $consultaSemana = RegistroIp::whereDate('created_at', '>=', $diaIniciaSemana)->count();
        $consultaMes = RegistroIp::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))->count();


        return response()->json([
            'visitsDay' => $consultaDia,
            'visitsWeek' => $consultaSemana,
            'visitsMonth' => $consultaMes,
            'visitsTotal' => $visitas->totalvisitas,
        ]);
    }
}
