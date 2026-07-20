<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nom' => '033', 'operateurId' => 1],
            ['nom' => '032', 'operateurId' => 2],
            ['nom' => '034', 'operateurId' => 3],
            ['nom' => '037', 'operateurId' => 3],
            ['nom' => '038', 'operateurId' => 2],
        ];

        $this->db->table('prefix')->insertBatch($data);
    }
}
