<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        // Mot de passe de reference (dev/demo) pour les 3 operateurs: "password"
        // Insere via le modele pour beneficier du hashage automatique (beforeInsert).
        $model = new \App\Models\OperateurModel();

        $data = [
            ['labelle' => 'Airtel Money', 'motDePasse' => 'password'],
            ['labelle' => 'MVola',        'motDePasse' => 'password'],
            ['labelle' => 'Orange Money', 'motDePasse' => 'password'],
        ];

        foreach ($data as $row) {
            $model->insert($row);
        }
    }
}
