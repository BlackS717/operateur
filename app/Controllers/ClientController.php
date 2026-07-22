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
        $rules = [
            'numeros' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Le(s) numero(s) du/des destinataire(s) est/sont obligatoire(s).',
                ],
            ],
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

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        // Parser les numeros (separes par des virgules)
        $numerosRaw = $this->request->getPost('numeros');
        $numeros = array_map('trim', explode(',', $numerosRaw));
        $numeros = array_filter($numeros, fn($n) => $n !== '');

        $montantTotal = (float) $this->request->getPost('montant');
        $inclureFrais = $this->request->getPost('inclureFrais') === '1';

        $result = $this->clientService->transfertMultiple(
            $this->userId(),
            $numeros,
            $montantTotal,
            $inclureFrais
        );
        return redirect()->to('/user')->with('reports', [$result['message']]);
    }

    public function epargne(){
        $userId = $this->userId();
        $epargne = $this->clientService->getEpargne($userId);
        return view('operateur/epargne_edit', ['epargne' => $epargne]);
    }

    public function epargneSubmit(){
        $userId = $this->userId();

        $rules = [
            'pourcentage'=> 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        if(!$this->clientService->updateEpargne($userId, $this->request->getPost('pourcentage'))){
            redirect()->back()->withInput()->with('reports', ['update'=>'Echec de la mise à jour']);
        }

        return redirect()->back()->withInput()->with('reports', ['update'=>'Epargne mise à jour']);
    }

    public function historique(): string
    {
        return view('client/historique', [
            'transactions' => $this->clientService->getHistorique($this->userId()),
            'userId' => $this->userId(),
        ]);
    }
}
