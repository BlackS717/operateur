<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\ClientService;

class ClientController extends BaseController
{
    protected $clientService;

    public function __construct()
    {
        $this->clientService = service('clientService');
    }

    private function userId(): int
    {
        return (int) session()->get('userId');
    }

    private function montantRules(): array
    {
        return [
            'montant' => [
                'rules' => 'required|numeric|greater_than_equal_to[100]|less_than_equal_to[2000000]',
                'errors' => [
                    'required' => 'Le montant est obligatoire.',
                    'numeric' => 'Le montant doit etre un nombre.',
                    'greater_than_equal_to' => 'Le montant minimum est de 100 Ar.',
                    'less_than_equal_to' => 'Le montant maximum est de 2 000 000 Ar.',
                ],
            ],
        ];
    }

    public function index(): string
    {
        return view('client/dashboard', [
            'solde' => $this->clientService->getSolde($this->userId()),
        ]);
    }

    public function depot(): string
    {
        return view('client/depot');
    }

    public function depotSubmit()
    {
        if (!$this->validate($this->montantRules())) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $result = $this->clientService->depot($this->userId(), (float) $this->request->getPost('montant'));
        return redirect()->to('/user')->with('reports', [$result['message']]);
    }

    public function retrait(): string
    {
        return view('client/retrait');
    }

    public function retraitSubmit()
    {
        if (!$this->validate($this->montantRules())) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $result = $this->clientService->retrait($this->userId(), (float) $this->request->getPost('montant'));
        return redirect()->to('/user')->with('reports', [$result['message']]);
    }

    public function transfert(): string
    {
        return view('client/transfert');
    }

    public function transfertSubmit()
    {
        $rules = array_merge($this->montantRules(), [
            'numero' => [
                'rules' => 'required|numeric|exact_length[10]',
                'errors' => [
                    'required' => 'Le numero du destinataire est obligatoire.',
                    'numeric' => 'Le numero du destinataire ne doit contenir que des chiffres.',
                    'exact_length' => 'Le numero du destinataire doit contenir 10 chiffres.',
                ],
            ],
        ]);

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $result = $this->clientService->transfert(
            $this->userId(),
            $this->request->getPost('numero'),
            (float) $this->request->getPost('montant')
        );
        return redirect()->to('/user')->with('reports', [$result['message']]);
    }

    public function historique(): string
    {
        return view('client/historique', [
            'transactions' => $this->clientService->getHistorique($this->userId()),
            'userId' => $this->userId(),
        ]);
    }
}
