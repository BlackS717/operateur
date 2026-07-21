<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['operateurId' => 1,'pourcentage' => 50],
            ['operateurId' => 2,'pourcentage' => 60],
            ['operateurId' => 3,'pourcentage' => 70],
        ];

        $this->db->table('promotions')->insertBatch($data);
    }
}
