<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Createutilisateur extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'numero' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'date_creation' => [
                'type'    => 'TEXT',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('numero');

        $this->forge->createTable('utilisateur');
    }

    public function down()
    {
        $this->forge->dropTable('utilisateur');
    }
}
