<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEpargne extends Migration
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
            'pourcentage' => [
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

        $this->forge->createTable('epargne');
    }


    public function down()
    {
        $this->forge->dropTable('epargne');
    }
}
