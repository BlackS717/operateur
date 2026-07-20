<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nom' => '032'],
            ['nom' => '033'],
            ['nom' => '034'],
            ['nom' => '037'],
            ['nom' => '038'],
        ];

        $this->db->table('prefix')->insertBatch($data);
    }
}