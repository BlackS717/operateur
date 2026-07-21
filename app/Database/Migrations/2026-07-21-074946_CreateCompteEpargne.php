<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompteEpargne extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'utilisateurId' => [
                'type'       => 'INTEGER',
            ],
            'montant' => [
                'type' => 'REAL',
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey(
            'utilisateurId',
            'utilisateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('compteEpargne');
    }


    public function down()
    {
        $this->forge->dropTable('compteEpargne');
    }
}
