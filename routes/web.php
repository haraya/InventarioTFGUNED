<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResponsableController;

Route::get('/', function () {
    return view('welcome');
});

//RUTA TEMPORAL
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::post('/roles/cambiar', [RolController::class, 'cambiarRol'])->name('roles.cambiar');
});

//Rutas para el rol de SuperAdministrador
Route::middleware(['auth', 'rol:Administrador|Superadmin'])->group(function () {
    Route::get('/admin/dashboard', [RolController::class, 'admin'])->name('admin.dashboard');
    Route::get('/admin/gestion-usuarios', [AdminController::class, 'gestion_usuarios'])->name('admin.gestion_usuarios');
    Route::get('/admin/instituciones', [AdminController::class, 'instituciones'])->name('admin.instituciones');
    Route::get('/admin/sedes', [AdminController::class, 'sedes'])->name('admin.sedes');
    Route::get('/admin/laboratorios', [AdminController::class, 'laboratorios'])->name('admin.laboratorios');
    Route::get('/admin/categorias', [AdminController::class, 'categorias'])->name('admin.categorias');
    Route::get('/admin/equipos', [AdminController::class, 'equipos'])->name('admin.equipos');
    Route::get('/admin/solicitudes', [AdminController::class, 'solicitudes'])->name('admin.solicitudes');
});

//Rutas para el rol de investigador
Route::middleware(['auth','rol:Responsable del activo'])->group(function () {
    Route::get('/responsable/dashboard', [RolController::class, 'responsable_activos'])->name('responsable_activos.dashboard');
    Route::get('/responsable/mis-equipos', [ResponsableController::class, 'mis_equipos'])->name('responsable_activos.mis_equipos');
});

//Ruta compartida para Responsable del activo y Usuario
Route::middleware(['auth','rol:Responsable del activo|Usuario'])->group(function () {
    Route::get('/listado-equipos', [ResponsableController::class, 'listado_equipos'])->name('responsable_activos.listado_equipos');
});



/*Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth'])->name('admin.dashboard');

Route::get('/investigador/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');*/

require __DIR__.'/auth.php';
