<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('OperateurSeeder');
        $this->call('PrefixSeeder');
        $this->call('UtilisateurSeeder');
        $this->call('PorteFeuilleSeeder');
        $this->call('TypeTransactionSeeder');
        $this->call('FraisSeeder');
        $this->call('CommissionSeeder');
        $this->call('TransactionsSeeder');
    }
}
