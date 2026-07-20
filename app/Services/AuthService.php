<?php

namespace App\Services;

use \App\Models\PrefixModel;
use \App\Models\UtilisateurModel;

class AuthService
{
    protected $prefixModel;
    protected $utilisateurModel;
    public function __construct()
    {
        $this->prefixModel = new PrefixModel();
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function getAllPrefixes()
    {

        return $this->prefixModel->findAll();
    }

    public function authenticate($numero)
    {
        $user = $this->utilisateurModel->getByNumero($numero);
        return $user;
    }

    public function register($numero)
    {
        $user = $this->utilisateurModel->register($numero);
        return $user;
    }
}
