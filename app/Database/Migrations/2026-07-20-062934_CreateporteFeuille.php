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
            'utilisateur_id' => [
                'type' => 'INTEGER',
            ],
            'solde' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('utilisateur_id');

        $this->forge->addForeignKey(
            'utilisateur_id',
            'utilisateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('porte_feuille');
    }

    public function down()
    {
        $this->forge->dropTable('porte_feuille');
    }
}
