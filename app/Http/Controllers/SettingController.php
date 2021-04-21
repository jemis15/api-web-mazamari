<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
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
    public function index()
    {
        $settings = [
            'name' => 'Municipalidad Distrital de Mazamari',
            'direction' => 'AV. PERU NRO. SN (COSTADO DE COLEGIO MARIATEGUI)',
            'phone' => '(064) 548187',
            'email' => 'munimazamarimcm@hotmail.com',
            'logo' => '/storage/images/settings/logo.png',
            'facebook' => 'municipalidadmazamari',
            'youtube' => 'UCyUSPxeeMi9rzDASLUfpGfg',
            'twitter' => 'munimazamari',
        ];

        return response()->json(['data' => $settings]);
    }
}
