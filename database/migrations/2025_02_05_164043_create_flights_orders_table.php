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
            $table->string('requesting_user');
            $table->unsignedBigInteger('destination_id');
            $table->date('departure_date');
            $table->date('return_date');
            $table->char('status', 1)->comment('S - solicitado, A - aprovado, C - cancelado');
            $table->string('status_text', 20)->comment('solicitado, aprovado, cancelado');
            $table->timestamps();
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
