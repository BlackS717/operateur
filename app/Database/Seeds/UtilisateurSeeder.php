<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UtilisateurSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'numero' => '0348041388',
                'roleId' => 1
            ],
            [
                'numero' => '0389299922',
                'roleId' => 2
            ],
            [
                'numero' => '0331256792',
                'roleId' => 2
            ]
        ];

        $this->db->table('utilisateur')->insertBatch($data);
    }
}