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
            'utilisateur_id' => [
                'type' => 'INTEGER',
            ],
            'type_transaction_id' => [
                'type' => 'INTEGER',
            ],
            'montant' => [
                'type' => 'REAL',
            ],
            'frais' => [
                'type' => 'REAL',
            ],
            'date_transaction' => [
                'type'    => 'TEXT',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey(
            'utilisateur_id',
            'utilisateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'type_transaction_id',
            'type_transaction',
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
