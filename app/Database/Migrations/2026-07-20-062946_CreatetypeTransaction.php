<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatetypeTransaction extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nom');

        $this->forge->createTable('type_transaction');
    }

    public function down()
    {
        $this->forge->dropTable('type_transaction');
    }
}
