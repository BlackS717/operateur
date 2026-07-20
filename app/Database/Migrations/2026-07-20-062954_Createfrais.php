<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Createfrais extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'minimum' => [
                'type' => 'REAL',
                'null' => true,
            ],
            'maximum' => [
                'type' => 'REAL',
            ],
            'valeur' => [
                'type' => 'REAL',
            ],
            'typeTransactionId' => [
                'type' => 'INTEGER',
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('typeTransactionId', 'typeTransaction', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('frais');

        $this->db->enableForeignKeyChecks();
        
    }

    public function down()
    {
        $this->forge->dropTable('frais');
    }
}
