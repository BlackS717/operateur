<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['utilisateurId' => 1, 'destinataireId' => 2, 'typeTransactionId' => 3, 'montant' => 10000,  'frais' => 100],
            ['utilisateurId' => 2, 'destinataireId' => 3, 'typeTransactionId' => 3, 'montant' => 25000,  'frais' => 200],
            ['utilisateurId' => 3, 'destinataireId' => 1, 'typeTransactionId' => 3, 'montant' => 5000,   'frais' => 50],
            ['utilisateurId' => 4, 'destinataireId' => 5, 'typeTransactionId' => 3, 'montant' => 40000,  'frais' => 400],
            ['utilisateurId' => 5, 'destinataireId' => 2, 'typeTransactionId' => 3, 'montant' => 15000,  'frais' => 200],
            ['utilisateurId' => 1, 'destinataireId' => 4, 'typeTransactionId' => 3, 'montant' => 60000,  'frais' => 800],
            ['utilisateurId' => 2, 'destinataireId' => 5, 'typeTransactionId' => 3, 'montant' => 8000,   'frais' => 100],
            ['utilisateurId' => 3, 'destinataireId' => 4, 'typeTransactionId' => 3, 'montant' => 120000, 'frais' => 1500],
        ];

        $this->db->table('transactions')->insertBatch($data);
    }
}
