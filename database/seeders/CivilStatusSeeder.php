<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = array(
            array('name' => "Single"),
            array('name' => "Married"),
            array('name' => "Concubinage"),
            array('name' => "Free Union"),
            array('name' => "Others")
        );

        DB::table('civil_statuses')->insert($status);
    }
}
