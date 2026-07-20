<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Createfrais extends Migration
{
    public function up()
{
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
    ]);

    $this->forge->addKey('id', true);

    $this->forge->createTable('frais');
}

public function down()
{
    $this->forge->dropTable('frais');
}
}
