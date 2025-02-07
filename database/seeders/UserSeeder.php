<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'CEO Onfly',
            'email' => 'ceo@onfly.com',
            'password' => bcrypt('onfly')
        ]);

        User::factory()->create([
            'name' => 'Bruno Assis',
            'email' => 'bruno@onfly.com',
            'password' => bcrypt('onfly')
        ]);
    }
}
