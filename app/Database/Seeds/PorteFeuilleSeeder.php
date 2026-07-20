<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PorteFeuilleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['utilisateurId' => 1, 'solde' => 100000, 'operateurId' => 3],
            ['utilisateurId' => 2, 'solde' => 75000,  'operateurId' => 2],
            ['utilisateurId' => 3, 'solde' => 50000,  'operateurId' => 1],
            ['utilisateurId' => 4, 'solde' => 150000, 'operateurId' => 2],
            ['utilisateurId' => 5, 'solde' => 30000,  'operateurId' => 3],
        ];

        $this->db->table('porteFeuille')->insertBatch($data);
    }
}
