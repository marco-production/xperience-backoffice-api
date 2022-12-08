<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $symptoms = array(
            array('id' => 6, 'name' => "None"),
            array('id' => 7, 'name' => "Sore throat"),
            array('id' => 8, 'name' => "Muscle pain"),
            array('id' => 9, 'name' => "Headache"),
            array('id' => 10, 'name' => "Runny nose"),
            array('id' => 11, 'name' => "Cough"),
            array('id' => 12, 'name' => "Shaking chills"),
            array('id' => 13, 'name' => "Breathing difficulty"),
            array('id' => 14, 'name' => "Fatigue"),
            array('id' => 15, 'name' => "Fever"),
        );

        DB::table('symptoms')->insert($symptoms);
    }
}
