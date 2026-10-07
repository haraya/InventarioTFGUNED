<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponsableController extends Controller
{
    //
    public function mis_equipos()
    {
        return view('responsable_activos.mis_equipos');
    }

    public function listado_equipos()
    {
        return view('responsable_activos.listado_equipos');
    }
}
