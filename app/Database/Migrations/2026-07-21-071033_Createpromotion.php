<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Createpromotion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'operateurId' => [
                'type'       => 'INTEGER',
            ],
            'pourcentage' => [
                'type' => 'REAL',
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey(
            'operateurId',
            'operateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('promotions');
    }


    public function down()
    {
        $this->forge->dropTable('promotions');
    }
}
