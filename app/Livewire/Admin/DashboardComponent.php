<?php

namespace App\Livewire\Admin;
use App\Models\SolicitudResponsable;
use Livewire\Component;

class DashboardComponent extends Component
{
    public $activeTab = 'equipos';

    public function render()
    {
        $pendientesResponsables = SolicitudResponsable::where('estado', 'pendiente')->count();
        return view('livewire.admin.dashboard-component', [
            'pendientesResponsables' => $pendientesResponsables
        ]);
    }
} 