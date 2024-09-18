<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LieuxTableSeeder extends Seeder
{
    public function run()
    {
        // Définir la liste des lieux
        $lieux = [
            ['nom' => 'Bakel'],
            ['nom' => 'Bambey'],
            ['nom' => 'Bignona'],
            ['nom' => 'Birkelane'],
            ['nom' => 'Cambérène'],
            ['nom' => 'Colobane'],
            ['nom' => 'Dagana'],
            ['nom' => 'Diourbel'],
            ['nom' => 'Dias'],
            ['nom' => 'Dieuppeul'],
            ['nom' => 'Dakar'],
            ['nom' => 'Fatick'],
            ['nom' => 'Fann'],
            ['nom' => 'Foundiougne'],
            ['nom' => 'Grand Dakar'],
            ['nom' => 'Goudiry'],
            ['nom' => 'Guédiawaye'],
            ['nom' => 'Hann Bel-Air'],
            ['nom' => 'Kaolack'],
            ['nom' => 'Kaffrine'],
            ['nom' => 'Kanel'],
            ['nom' => 'Kédougou'],
            ['nom' => 'Kébémer'],
            ['nom' => 'Kolda'],
            ['nom' => 'Koungheul'],
            ['nom' => 'Koumpentoum'],
            ['nom' => 'Linguère'],
            ['nom' => 'Louga'],
            ['nom' => 'Malem Hodar'],
            ['nom' => 'Matam'],
            ['nom' => 'Mbacké'],
            ['nom' => 'Mbour'],
            ['nom' => 'Médina'],
            ['nom' => 'Médina Yoro Foulah'],
            ['nom' => 'Mermoz-Sacré Cœur'],
            ['nom' => 'Ndiebène Gandiol'],
            ['nom' => 'Nioro du Rip'],
            ['nom' => 'Oussouye'],
            ['nom' => 'Ouakam'],
            ['nom' => 'Parcelles Assainies'],
            ['nom' => 'Patte d\'Oie'],
            ['nom' => 'Pikine'],
            ['nom' => 'Podor'],
            ['nom' => 'Ranérou-Ferlo'],
            ['nom' => 'Rufisque'],
            ['nom' => 'Saint-Louis'],
            ['nom' => 'Salémata'],
            ['nom' => 'Sédhiou'],
            ['nom' => 'Sokone'],
            ['nom' => 'Saraya'],
            ['nom' => 'Tambacounda'],
            ['nom' => 'Taïf'],
            ['nom' => 'Thiès'],
            ['nom' => 'Tivaouane'],
            ['nom' => 'Touba'],
            ['nom' => 'Vélingara'],
            ['nom' => 'Yoff'],
            ['nom' => 'Ziguinchor'],
        ];

        // Supprimer les anciennes entrées
        DB::table('lieux')->truncate();

        // Insérer les nouvelles données
        DB::table('lieux')->insert($lieux);
    }
}
