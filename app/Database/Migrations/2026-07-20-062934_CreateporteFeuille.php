<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateporteFeuille extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'utilisateurId' => [
                'type' => 'INTEGER',
            ],
            'solde' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('utilisateurId');

        $this->forge->addForeignKey(
            'utilisateurId',
            'utilisateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('porteFeuille');
    }

    public function down()
    {
        $this->forge->dropTable('porteFeuille');
    }
}
