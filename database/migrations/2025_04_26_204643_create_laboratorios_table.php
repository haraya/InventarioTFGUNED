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
        Schema::create('invent_rec_invest_cnr_laboratorios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('visible')->default(true);
            // ID de la institucion
            $table->unsignedBigInteger('institucion_id')->nullable();
            $table->foreign('institucion_id')
                  ->references('id')
                  ->on('invent_rec_invest_cnr_institucions')
                  ->onDelete('set null');
                  
            // ID de la sede
            $table->unsignedBigInteger('sede_id')->nullable();
            $table->foreign('sede_id')
                  ->references('id')
                  ->on('invent_rec_invest_cnr_sedes')
                  ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invent_rec_invest_cnr_laboratorios');
    }
};
