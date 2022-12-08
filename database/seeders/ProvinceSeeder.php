<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Geolocation\Province;
use File;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/Provinces.json");
        $provinces = json_decode($json);
        
        foreach ($provinces as $value) {
            Province::create([
                "id" => $value->Id,
                "name" => $value->ToponomyName,
                "geo_code" => $value->GeoCode,
                "macro_region_id" => $value->MacroRegion,
                "region" => $value->Region,
            ]);
        }
    }
}
