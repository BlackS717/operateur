<?php

namespace App\Services;

use \App\Models\PrefixModel;

class AuthService
{
    protected $prefixModel;
    public function __construct()
    {
        $this->prefixModel = new PrefixModel();
    }

    public function getAllPrefixes()
    {

        return $this->prefixModel->findAll();
    }
}
