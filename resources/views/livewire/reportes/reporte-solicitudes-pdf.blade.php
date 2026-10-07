<div>
    @if (session()->has('error'))

    



  
        

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
       <strong>{{ session('error') }}</strong> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      
      
        @endif 

        {{--<button wire:click="exportar" class="btn btn-primary">
            
        </button>--}}


     

      <div class="col-auto mb-2">
        <div class="d-flex align-items-center border border-primary rounded shadow-sm card-hover px-3 py-2" style="cursor:pointer; min-width: 180px;" wire:click="exportar">
            <i class="bi bi-hourglass-split fs-5 text-primary me-2"></i>
            <span class="fw-semibold flex-grow-1" style="font-size: 1rem;">Exportar PDF de Solicitudes de Equipos</span>
            
        </div>
    </div>
       
  
</div>
