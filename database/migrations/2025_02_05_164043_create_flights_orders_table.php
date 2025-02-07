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
        Schema::create('flights_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('solicitante')->index();
            $table->string('destino');
            $table->date('data_ida');
            $table->date('data_volta');
            $table->char('status_codigo', 1)->comment('S - solicitado, A - aprovado, C - cancelado')->index();
            $table->string('status', 20)->comment('solicitado, aprovado, cancelado');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights_orders');
    }
};
