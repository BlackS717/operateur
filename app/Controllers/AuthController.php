<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\AuthService;

class AuthController extends BaseController
{

    protected $authService;
    public function __construct()
    {
        $this->authService = service('authService');
    }
    public function index(): string
    {
        return view('auth/login');
    }

    public function buildNumberValidation(): array
    {
        $validNumbers = $this->authService->getAllPrefixes();

        $str = "required|numeric|exact_length[10]|regex_match[/^(";
        for ($i = 0; $i < count($validNumbers); $i++) {
            $str .= preg_quote($validNumbers[$i]['nom'], '/');
            if ($i < count($validNumbers) - 1) {
                $str .= "|";
            }
        }
        $str .= ")/]";

        return [
            'rules' => $str,
            'errors' => [
                'required' => 'Le numero de telephone est obligatoire.',
                'numeric' => 'Le numero de telephone ne doit contenir que des chiffres.',
                'exact_length' => 'Le numero de telephone doit contenir 10 chiffres.',
                'regex_match' => "Ce prefixe n'est pas pris en charge par l'operateur.",
            ],
        ];
    }

    public function authenticate(): RedirectResponse
    {

        $rules = [
            'phone' => $this->buildNumberValidation(),
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $user = $this->authService->authenticate($this->request->getPost('phone'));
        if (!$user) {
            $user = $this->authService->register($this->request->getPost('phone'));
            }
        session()->set('userId', $user['id']);
        session()->set('roleId', $user['roleId']);
        if ($user['roleId'] == 1) {
            return redirect()->to('/admin');
        }
        return redirect()->to('/user');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
