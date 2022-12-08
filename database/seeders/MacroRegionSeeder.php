<?php

namespace Database\Seeders;

use App\Models\Geolocation\MacroRegion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MacroRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $macroRegions = array(
            array('name' => "Región Norte"),
            array('name' => "Región Suroeste"),
            array('name' => "Región Sureste"),
        );

        DB::table('macro_regions')->insert($macroRegions);
    }
}
