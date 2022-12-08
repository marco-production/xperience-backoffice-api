<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            CountrySeeder::class,
            RelationshipSeeder::class,
            TransportationSeeder::class,
            SymptomSeeder::class,
            AirlineSeeder::class,
            CurrencySeeder::class,
            CivilStatusSeeder::class,
            HotelSeeder::class,
            MotiveSeeder::class,
            OccupationSeeder::class,
            PortSeeder::class,
            MacroRegionSeeder::class,
            ProvinceSeeder::class,
            MunicipalitySeeder::class,
            SectorSeeder::class,
            UserSeeder::class,
        ]);
    }
}
