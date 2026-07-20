<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FraisSeeder extends Seeder
{
    public function run()
    {
        // Bareme applique au type "Transfert" (typeTransactionId = 3),
        // conforme aux donnees de reference de database/new.sql.
        $data = [
            ['minimum' => 100,      'maximum' => 1000,    'valeur' => 50,   'typeTransactionId' => 3],
            ['minimum' => 1001,     'maximum' => 5000,    'valeur' => 50,   'typeTransactionId' => 3],
            ['minimum' => 5001,     'maximum' => 10000,   'valeur' => 100,  'typeTransactionId' => 3],
            ['minimum' => 10001,    'maximum' => 25000,   'valeur' => 200,  'typeTransactionId' => 3],
            ['minimum' => 25001,    'maximum' => 50000,   'valeur' => 400,  'typeTransactionId' => 3],
            ['minimum' => 50001,    'maximum' => 100000,  'valeur' => 800,  'typeTransactionId' => 3],
            ['minimum' => 100001,   'maximum' => 250000,  'valeur' => 1500, 'typeTransactionId' => 3],
            ['minimum' => 250001,   'maximum' => 500000,  'valeur' => 1500, 'typeTransactionId' => 3],
            ['minimum' => 500001,   'maximum' => 1000000, 'valeur' => 2500, 'typeTransactionId' => 3],
            ['minimum' => 1000001,  'maximum' => 2000000, 'valeur' => 5000, 'typeTransactionId' => 3],
        ];

        $this->db->table('frais')->insertBatch($data);
    }
}
