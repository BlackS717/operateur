<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PorteFeuilleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'utilisateurId' => 1,
                'solde' => 50000
            ],
            [
                'utilisateurId' => 2,
                'solde' => 150000
            ],
            [
                'utilisateurId' => 3,
                'solde' => 75000
            ]
        ];

        $this->db->table('porteFeuille')->insertBatch($data);
    }
}
