<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\ConvertToUTF8;
use App\Models\Geolocation\Country;
use File;

class CountrySeeder extends Seeder
{
    use ConvertToUTF8;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/countries.json");
        $countries = json_decode($json);
        
        foreach ($countries as $value) {
            Country::create([
                "id" => $value->id,
                "name" => isset($value->name) ? $this->convertToUtf8($value->name) : null,
                "official_name" => isset($value->OfficialName) ? $this->convertToUtf8($value->OfficialName) : null,
                "iso2" => $value->iso2,
                "iso3" => $value->iso3,
                "latitude" => $value->latitude,
                "longitude" => $value->longitude,
                "zoom" => $value->zoom,
                "enabled" => $value->enabled,
            ]);
        }
    }
}
