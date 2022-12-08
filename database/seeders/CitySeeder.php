<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\ConvertToUTF8;
use Illuminate\Support\Facades\DB;
use App\Models\Geolocation\City;
use File;

class CitySeeder extends Seeder
{
    use ConvertToUTF8;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/Cities.json");
        $cities = json_decode($json);
        
        foreach ($cities as $value) {

            City::create([
                "id" => $value->Id,
                "name" => isset($value->Name) ? $this->convertToUtf8($value->Name) : null,
                "iso2" => $value->Iso2CountryCode,
                "latitude" => isset($value->Latitude) ? $this->convertToUtf8($value->Latitude) : null,
                "longitude" => isset($value->Longitude) ? $this->convertToUtf8($value->Longitude) : null,
                'state' => isset($value->State) ? $this->convertToUtf8($value->State) : null,
                "latitude" => $value->Latitude,
                "longitude" => $value->Longitude,
                'state' => $value->State,
                "latitude" => $value->Latitude,
                "longitude" => $value->Longitude,
                'state' => $value->State,
                'state_code' => $value->StateCode
            ]);
        }
    }
}
