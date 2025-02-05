<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::factory()->create([
            'name' => 'Ipatinga/MG'
        ]);
        Location::factory()->create([
            'name' => 'Belo Horizonte/MG'
        ]);
    }
}
