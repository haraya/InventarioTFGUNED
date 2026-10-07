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
        Schema::create('invent_rec_invest_cnr_solicituds', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_solicitud');
            $table->date('fecha_aproximada_devolucion');
            $table->date('fecha_exacta_devolucion')->nullable();
            $table->string('estado_solicitud')->default('Pendiente');

            $table->unsignedBigInteger('equipo_id')->nullable();
            $table->foreign('equipo_id')
                  ->references('id')
                  ->on('invent_rec_invest_cnr_equipos')
                  ->onDelete('set null');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invent_rec_invest_cnr_solicituds');
    }
};
