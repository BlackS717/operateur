<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EpargneSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['utilisateurId' => 1, 'pourcentage' => 15],
            ['utilisateurId' => 2, 'pourcentage' => 20],
            ['utilisateurId' => 3, 'pourcentage' => 5],
            ['utilisateurId' => 4, 'pourcentage' => 5.5],

        ];

        $this->db->table('epargne')->insertBatch($data);
    }
}
