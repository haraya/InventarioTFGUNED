<?php

namespace App\Livewire\SolicitudResponsable;

use Livewire\Component;
use App\Models\SolicitudResponsable;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
class GestionSolicitudResponsable extends Component
{
    use WithPagination;

    public $buscar;
    public $perPage = 10;

    public function aprobarSolicitud($id)
    {
        $solicitud = SolicitudResponsable::find($id);
        $solicitud->update([
            'estado' => 'aprobado',
            'fecha_aprobacion' => now()
        ]);

        // Actualizar el rol del usuario a responsable
        $user = $solicitud->responsable;
        if($user->hasRole('Usuario')){
            $user->removeRole('Usuario');
        }
        $user->assignRole('Responsable del activo');

        $user->update([
            'rol_actual' => 'Responsable del activo'
        ]);
       

        session()->flash('success', 'Solicitud aprobada correctamente');
    }

    public function rechazarSolicitud($id)
    {
        $solicitud = SolicitudResponsable::find($id);
        $solicitud->update([
            'estado' => 'rechazado',
            'fecha_aprobacion' => now()
        ]);

        session()->flash('success', 'Solicitud rechazada correctamente');
    }
    public function render()
    {
        $query = SolicitudResponsable::query()
            ->with('responsable');

        if ($this->buscar) {
            $query->whereHas('responsable', function($q) {
                $q->where('name', 'like', '%' . $this->buscar . '%');
            });
        }

        $solicitudes = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.solicitud-responsable.gestion-solicitud-responsable', [
            'solicitudes' => $solicitudes
        ]);
    }
}
