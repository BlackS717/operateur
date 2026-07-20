<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Createtransactions extends Migration
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
            'typeTransactionId' => [
                'type' => 'INTEGER',
            ],
            'montant' => [
                'type' => 'REAL',
            ],
            'frais' => [
                'type' => 'REAL',
            ],
            'dateTransaction' => [
                'type'    => 'TEXT',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey(
            'utilisateurId',
            'utilisateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'typeTransactionId',
            'typeTransaction',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('transaction');
    }

    public function down()
    {
        $this->forge->dropTable('transaction');
    }
}
