<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relationships = array(
            array('name' => "Friend"),
            array('name' => "Co-workers"),
            array('name' => "Family"),
            array('name' => "Couple"),
            array('name' => "Me")
        );

        DB::table('relationships')->insert($relationships);
    }
}
