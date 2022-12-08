<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Geolocation\Municipality;
use File;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/Municipality.json");
        $municipalities = json_decode($json);
        
        foreach ($municipalities as $value) {
            Municipality::create([
                "name" => $value->ToponomyName,
                "geo_code" => $value->GeoCode,
                "province_id" => $value->ProvinceId,
                "municipalities" => $value->Municipalities,
            ]);
        }
    }
}
