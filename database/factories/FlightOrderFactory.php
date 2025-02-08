<?php

namespace Database\Factories;

use App\Models\FlightOrder;
use Illuminate\Database\Eloquent\Factories\Factory;


class FlightOrderFactory extends  factory
{
    protected $model = FlightOrder::class;

    public function definition(): array
    {
        return [
            'user_id'       => 1,//\App\Models\User::factory(),
            'solicitante'   => fake()->name(),
            'destino'       => 'Ipatinga',//fake()->city(),
            'data_ida'      => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'data_volta'    => fake()->dateTimeBetween('+1 month', '+2 months')->format('Y-m-d'),
            'status_codigo' => fake()->randomElement(['S', 'C', 'A']), // Pendente, Confirmado, Finalizado
            'status'        => fake()->randomElement(['pendente', 'confirmado', 'finalizado']),
            'created_at'    => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d')
        ];
    }
}
