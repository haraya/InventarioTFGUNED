<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invent_rec_invest_cnr_equipos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_activo')->nullable();
            $table->string('nombre');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('serie')->nullable();
            $table->string('estado')->default('Disponible');
            $table->date('fecha_adquisicion')->nullable();

            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->foreign('categoria_id')
                  ->references('id')
                  ->on('invent_rec_invest_cnr_categoria')
                  ->onDelete('set null')->default(null);

            $table->unsignedBigInteger('laboratorio_id')->nullable();
            $table->foreign('laboratorio_id')
                  ->references('id')
                  ->on('invent_rec_invest_cnr_laboratorios')
                  ->onDelete('set null')->default(null);

            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->foreign('responsable_id')
                  ->references('id')
                  ->on('users') 
                  ->onDelete('set null')->default(null);

            
            $table->text('caracteristicas')->nullable();
            $table->text('condiciones_uso')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invent_rec_invest_cnr_equipos');
    }
};
