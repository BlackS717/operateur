<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeTransactionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'Depot'
            ],
            [
                'nom' => 'Retrait'
            ],
            [
                'nom' => 'Transfert'
            ]
        ];

        $this->db->table('typeTransaction')->insertBatch($data);
    }
}
