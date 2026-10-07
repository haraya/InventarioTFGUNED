<?php

namespace App\Livewire\SolicitudResponsable;

use Livewire\Component;
use App\Models\SolicitudResponsable;
use Illuminate\Support\Facades\Auth;

class SolicitudResponsableStatus extends Component
{
    public $solicitud;

    public function mount()
    {
        $this->solicitud = SolicitudResponsable::where('respon_id', Auth::id())
            ->where('estado', 'pendiente')
            ->first();
    }
    public function render()
    {
        return view('livewire.solicitud-responsable.solicitud-responsable-status');
    }
}
