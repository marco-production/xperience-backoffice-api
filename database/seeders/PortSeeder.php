<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeakEntities\Port;
use File;

class PortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = File::get("database/data/Ports.json");

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

        
        $ports = json_decode($file);
        
        foreach ($ports as $value) {
            Port::create([
                "code" => $value->Code,
                "transportation_id" => $value->TransportationMethodId,
                "place" => ucwords(strtolower($value->Place)),
                "name" => ucwords(strtolower($value->Name)),
                "dominican_port" => $value->IsDominicanPort
            ]);
        }
    }
}
