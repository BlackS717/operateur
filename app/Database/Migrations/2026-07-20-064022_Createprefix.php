<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Createprefix extends Migration
{
public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'TEXT',
                'null'       => false,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nom');

        $this->forge->createTable('prefix');
    }

    public function down()
    {
        $this->forge->dropTable('prefix');
    }
}
