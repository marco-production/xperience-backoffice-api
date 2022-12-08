<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $motives = array(
            array('name' => "Playtime"),
            array('name' => "Business"),
            array('name' => "Convention - Conference"),
            array('name' => "Studies"),
            array('name' => "Visit Friends and / or Relatives"),
            array('name' => "Others")
        );

        DB::table('motives')->insert($motives);
    }
}
