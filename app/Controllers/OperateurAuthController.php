<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use App\Services\OperateurService;

class OperateurAuthController extends BaseController
{
    protected $operateurService;

    public function __construct()
    {
        $this->operateurService = service('operateurService');
    }

    public function index(): string
    {
        return view('operateur/login');
    }

    private function loginRules(): array
    {
        return [
            'labelle' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Le nom de l'operateur est obligatoire.",
                ],
            ],
            'motDePasse' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Le mot de passe est obligatoire.',
                ],
            ],
        ];
    }

    public function authenticate(): RedirectResponse
    {
        if (!$this->validate($this->loginRules())) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $operateur = $this->operateurService->authenticate(
            $this->request->getPost('labelle'),
            $this->request->getPost('motDePasse')
        );

        if (!$operateur) {
            return redirect()->back()->withInput()->with('reports', ['Identifiants incorrects.']);
        }

        session()->set('operateurId', $operateur['id']);
        session()->set('operateurLabelle', $operateur['labelle']);

        return redirect()->to('/admin');
    }

    public function logout(): RedirectResponse
    {
        session()->remove(['operateurId', 'operateurLabelle']);
        return redirect()->to('/admin/login');
    }
}
