<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateOperateur extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'labelle' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'motDePasse' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'dateCreation' => [
                'type'    => 'TEXT',
                'null'    => false,
                'default' => new RawSql("(datetime('now'))"),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('labelle');

        $this->forge->createTable('operateur');
    }

    public function down()
    {
        $this->forge->dropTable('operateur');
    }
}
