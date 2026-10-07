<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function gestion_usuarios()
    {
        return view('admin.gestionusuarios');
    }

    public function instituciones()
    {
        return view('admin.instituciones');
    }
    public function laboratorios()
    {
        return view('admin.laboratorios');
    }
    public function sedes()
    {
        return view('admin.sedes');
    }

    public function categorias()
    {
        return view('admin.categorias');
    }
    public function equipos()
    {
        return view('admin.equipos');
    }

    public function solicitudes()
    {
        return view('admin.solicitudes');
    }

  
}
