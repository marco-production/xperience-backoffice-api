<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeakEntities\Airline;
use File;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = File::get("database/data/Airlines.json");

        // This will remove unwanted characters.
        // Check http://www.php.net/chr for details
        for ($i = 0; $i <= 31; ++$i) { 
            $file = str_replace(chr($i), "", $file); 
        }
        $file = str_replace(chr(127), "", $file);

        // This is the most common part
        // Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
        // here we detect it and we remove it, basically it's the first 3 characters 
        if (0 === strpos(bin2hex($file), 'efbbbf')) {
            $file = substr($file, 3);
        }

        $airlines = json_decode($file);
        
        foreach ($airlines as $value) {
            Airline::create([
                "id" => $value->Id,
                "code" => $value->Code,
                "name" => $value->Name,
                "origin_code" => $value->OriginCode,
                "observation" => isset($value->Observation) ? $value->Observation : null,
            ]);
        }
    }
}
