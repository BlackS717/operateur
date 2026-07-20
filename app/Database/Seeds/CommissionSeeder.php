<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommissionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['source' => 1, 'destinataire' => 2, 'pourcentage' => 1.5],
            ['source' => 2, 'destinataire' => 1, 'pourcentage' => 1.5],
            ['source' => 1, 'destinataire' => 3, 'pourcentage' => 2.0],
            ['source' => 3, 'destinataire' => 1, 'pourcentage' => 2.0],
            ['source' => 2, 'destinataire' => 3, 'pourcentage' => 1.8],
            ['source' => 3, 'destinataire' => 2, 'pourcentage' => 1.8],
        ];

        $this->db->table('commission')->insertBatch($data);
    }
}
