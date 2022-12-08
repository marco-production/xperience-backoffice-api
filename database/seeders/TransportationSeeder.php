<?php

namespace Database\Seeders;

use App\Models\WeakEntities\Transportation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transportation::create([
            'name' => 'Ground Transportation'
        ]);

        Transportation::create([
            'name' => 'Marine Transport'
        ]);

        Transportation::create([
            'name' => 'Air Transport'
        ]);
    }
}
