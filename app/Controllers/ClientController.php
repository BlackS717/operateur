<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\ClientService;

class ClientController extends BaseController
{
    private $clientService;

    public function __construct()
    {
        $this->clientService = service('clientService');
    }

    public function index()
    {
        return view('client/index');
    }

    public function getSolde($clientId)
    {
        $solde = $this->clientService->getSoldeByClientId($clientId);
        if ($solde !== null) {
            return view('client/solde', ['solde' => $solde]);
        } else {
            return view('client/solde', ['solde' => 'Solde du client not found']);
        }
    }
}
