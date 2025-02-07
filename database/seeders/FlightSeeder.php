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
            'requesting_user_id' => 1,
            'destination_id' => 1,
            'departure_date' => Carbon::now()->addDays(5)->format("Y-m-d"),
            'return_date' => Carbon::now()->addDays(10)->format("Y-m-d"),
            'status' => 'S',
            'status_text' => 'Solicitado',
        ]);

        FlightOrder::create([
            'requesting_user_id' => 1,
            'destination_id' => 1,
            'departure_date' => Carbon::now()->addDays(12)->format("Y-m-d"),
            'return_date' => Carbon::now()->addDays(17)->format("Y-m-d"),
            'status' => 'S',
            'status_text' => 'Solicitado',
        ]);
    }
}
