<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'utilisateurId' => 2,
                'destinataireId' => 3,
                'typeTransactionId' => 1,
                'montant' => 50000,
                'frais' => 400
            ],
            [
                'utilisateurId' => 2,
                'destinataireId' => 3,
                'typeTransactionId' => 2,
                'montant' => 10000,
                'frais' => 100
            ],
            [
                'utilisateurId' => 3,
                'destinataireId' => 2,
                'typeTransactionId' => 1,
                'montant' => 25000,
                'frais' => 200
            ],
            [
                'utilisateurId' => 3,
                'destinataireId' => 2,
                'typeTransactionId' => 3,
                'montant' => 5000,
                'frais' => 50
            ],
            [
                'utilisateurId' => 3,
                'destinataireId' => 2,
                'typeTransactionId' => 2,
                'montant' => 3000,
                'frais' => 50
            ]
        ];

        $this->db->table('transactions')->insertBatch($data);
    }
}
