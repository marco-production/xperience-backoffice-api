<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeakEntities\Currency;
use File;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/Currencies.json");
        $currency = json_decode($json);
        
        foreach ($currency as $value) {
            Currency::create([
                "code" => $value->code,
                "name" => $value->name,
            ]);
        }
    }
}
