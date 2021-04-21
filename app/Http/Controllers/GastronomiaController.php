<?php

namespace App\Http\Controllers;

use App\Models\Agroindustria;
use App\Models\Gastronomia;
use App\Models\Hotel;
use App\Models\Restaurante;
use App\Models\RestaurantePlato;
use App\Models\Turismo;

class GastronomiaController extends Controller
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

    // GET v1/gerencias
    public function index()
    {
        $gastronomias = Gastronomia::with([
            'images' => function ($query) {
                $query->select('idgf', 'idga', 'img');
            },
            'lugaresADegustar' => function ($query) {
                $query->select('nombre', 'numero', 'fb', 'email');
            }
        ])
            ->where('estado', '1')
            ->get(['idga', 'nombre', 'origen', 'ingredientes', 'preparacion']);

        foreach ($gastronomias as $gastronomia) {
            foreach ($gastronomia->images as $image) {
                $image->img = 'https://web.munimazamari.gob.pe/admin/contenido/archivos/img/' . $image->img;
            }
        }

        return response()->json($gastronomias);
    }

    public function getTurismos()
    {
        $turismos = Turismo::with([
            'images' => function ($query) {
                $query->select('idf', 'idtu', 'img');
            },
            'actividades' => function ($query) {
                $query->select('actividad');
            },
            'operadores' => function ($query) {
                $query->select('nombre', 'ubicacion', 'email');
            },
            'servicios' => function ($query) {
                $query->select('servicio');
            }
        ])
            ->where('estado', '1')
            ->get([
                'idtu',
                'nombre',
                'descri as descripcion',
                'ubi as ubicacion',
                'acceso',
                'tempo',
                'ema',
                'llegar'
            ]);

        foreach ($turismos as $turismo) {
            foreach ($turismo->images as $image) {
                $image->img = 'https://web.munimazamari.gob.pe/admin/contenido/archivos/img/' . $image->img;
            }
        }

        return response()->json($turismos);
    }

    public function getAgroindustrias()
    {
        $agroindustrias = Agroindustria::with([
            'images' => function ($query) {
                $query->select('idpro', 'img');
            }
        ])
            ->where('estado', '1')
            ->get([
                'idpro',
                'nom as nombre',
                'des as descripcion',
                'dir as direccion',
                'tel as telefono',
                'face as facebook',
                'tel as telefono',
                'ws as whatsapp',
                'pg as web',
                'pdf'
            ]);

        foreach ($agroindustrias as $agroindustria) {
            foreach ($agroindustria->images as $image) {
                $image->img = 'https://web.munimazamari.gob.pe/admin/contenido/archivos/img/' . $image->img;
            }
            $agroindustria->pdf = 'https://web.munimazamari.gob.pe/admin/contenido/archivos/agro/' . $agroindustria->pdf;
        }

        return response()->json($agroindustrias);
    }

    public function getHabitaciones()
    {
        try {
            $hoteles = Hotel::with([
                'images' => function ($query) {
                    $query->select('idh', 'img');
                },
                'habitaciones' => function ($query) {
                    $query->select('habi as nombre');
                }
            ])
                ->where('estado', '1')
                ->get([
                    'idh',
                    'nom as nombre',
                    'atencion',
                    'dir as direccion',
                    'cel as celular',
                    'tel as telefono',
                    'fb as facebook'
                ]);

            foreach ($hoteles as $hotel) {
                foreach ($hotel->images as $image) {
                    $image->img = 'https://web.munimazamari.gob.pe/admin/contenido/archivos/img/' . $image->img;
                }
            }
        } catch (\Throwable $th) {
            return response($th->getMessage(), 400);
        }

        return response()->json($hoteles);
    }

    public function getRestaurantes()
    {
        $restaurantes = [];
        try {
            $restaurantes = Restaurante::where('estado', 1)
                ->with([
                    'images' => function ($query) {
                        $query->select('idim', 'idre', 'img');
                    }
                ])
                ->get([
                    'idre as id',
                    'nom as nombre',
                    'direc as direccion',
                    'refe as referencia',
                    'fb as facebook',
                    'ema as email',
                    'descr as descripcion',
                    'mensaje',
                    'horario as horarios',
                    'reservas',
                    'tipo',
                    'logo',
                ]);

            foreach ($restaurantes as $restaurante) {
                $platos = RestaurantePlato::join('r_carta', 'r_carta.idc', 'r_carta_plato.idc')
                    ->join('r_restau_comi', 'r_restau_comi.idrc', 'r_carta.idc')
                    ->join('r_restau', 'r_restau.idre', 'r_restau_comi.idre')
                    ->where('r_restau.idre', $restaurante->id)
                    ->pluck('r_carta_plato.plato');

                $restaurante->platos = $platos;
            }

            foreach ($restaurantes as $restaurante) {
                $restaurante->logo = 'https://web.munimazamari.gob.pe/admin/contenido/archivos/img/' . $restaurante->logo;
                foreach ($restaurante->images as $image) {
                    $image->img = 'https://web.munimazamari.gob.pe/admin/contenido/archivos/img/' . $image->img;
                }
            }
        } catch (\Throwable $th) {
            return response($th->getMessage(), 400);
        }
        return response()->json($restaurantes);
    }
}
