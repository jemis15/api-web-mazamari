<?php

namespace App\Http\Controllers;

use App\Models\ModalBienvenida;

class ModalNotificationController extends Controller
{
    // GET v1/notificationmodal/actives
    public function actives()
    {
        $modals = ModalBienvenida::where('active', 1)->get(['id', 'type', 'url']);
        return response()->json(['data' => $modals]);
    }
}
