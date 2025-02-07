<?php

namespace Database\Seeders;

use App\Models\FlightOrder;
use App\Models\Location;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FlightOrder::create([
            'user_id' => 2,
            'solicitante' => "Bruno Assis",
            'destino' => "Belo Horizonte/MG",
            'data_ida' => Carbon::now()->addDays(5)->format("Y-m-d"),
            'data_volta' => Carbon::now()->addDays(10)->format("Y-m-d"),
            'status_codigo' => 'A',
            'status' => 'aprovado',
        ]);

        FlightOrder::create([
            'user_id' => 1,
            'solicitante' => "CEO Onfly",
            'destino' => "Ipatinga/MG",
            'data_ida' => Carbon::now()->addDays(12)->format("Y-m-d"),
            'data_volta' => Carbon::now()->addDays(17)->format("Y-m-d"),
            'status_codigo' => 'S',
            'status' => 'solicitado',
        ]);
    }
}
