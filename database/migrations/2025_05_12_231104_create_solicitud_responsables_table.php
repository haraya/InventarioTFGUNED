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
        Schema::create('invent_rec_invest_cnr_solict_responsables', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_solicitud');
            $table->unsignedBigInteger('respon_id')->nullable();
            $table->foreign('respon_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            $table->string('estado')->default('pendiente');
            $table->date('fecha_aprobacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invent_rec_invest_cnr_solict_responsables');
    }
};
