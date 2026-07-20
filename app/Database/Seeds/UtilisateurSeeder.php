<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UtilisateurSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['numero' => '0348041388'],
            ['numero' => '0389299922'],
            ['numero' => '0331256792'],
            ['numero' => '0324567890'],
            ['numero' => '0379988776'],
        ];

        $this->db->table('utilisateur')->insertBatch($data);
    }
}
