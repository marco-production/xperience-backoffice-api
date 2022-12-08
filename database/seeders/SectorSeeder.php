<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Geolocation\Sector;
use App\Models\Geolocation\Municipality;
use File;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/Sectors.json");
        $sectors = json_decode($json);
        
        foreach ($sectors as $value) {

            $municipality = Municipality::where('province_id', $value->ProvinceId)->where('municipalities', $value->Municipalities)->first();

            Sector::create([
                "name" => $value->ToponomyName,
                "geo_code" => $value->GeoCode,
                "municipality_id" => $municipality->id,
                "municipal_district" => $value->MunicipalDistrict,
                "neighborhood" => $value->Neighborhood,
                "section" => $value->Section,
            ]);
        }
    }
}
