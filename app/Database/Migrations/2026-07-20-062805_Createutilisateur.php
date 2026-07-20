<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUtilisateur extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'numero' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'dateCreation' => [
                'type'    => 'TEXT',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'roleId' => [
                'type' => 'INTEGER',
                'default' => '2',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('numero');

        $this->forge->addForeignKey(
            'roleId',
            'role',
            'id',
            'CASCADE',
            'SET NULL'
        );

        $this->forge->createTable('utilisateur');
    }

    public function down()
    {
        $this->forge->dropTable('utilisateur');
    }
}
