<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CompteEpargneSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['utilisateurId' => 1, 'montant' => 1000],
            ['utilisateurId' => 2, 'montant' => 20],
            ['utilisateurId' => 3, 'montant' => 0],
            ['utilisateurId' => 4, 'montant' => 500],

        ];

        $this->db->table('compteEpargne')->insertBatch($data);
    }
}
