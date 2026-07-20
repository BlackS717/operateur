<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nom' => 'admin'],
            ['nom' => 'client'],
        ];

        $this->db->table('role')->insertBatch($data);
    }
}