<?php

namespace App\Services;
use \App\Models\UtilisateurModel;
class ClientService {

    private $clientModel;
    public function __construct(){
        $this->clientModel = new UtilisateurModel();
    }

    public function getAllClients() {
        return $this->clientModel->findAll();
    }

    public 
}
