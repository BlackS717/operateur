<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommission extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'source' => [
                'type' => 'INTEGER',
            ],
            'destinataire' => [
                'type' => 'INTEGER',
            ],
            'pourcentage' => [
                'type' => 'REAL',
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('source', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('destinataire', 'operateur', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('commission');
    }

    public function down()
    {
        $this->forge->dropTable('commission');
    }
}
