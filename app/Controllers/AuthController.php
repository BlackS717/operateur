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

    public function buildNumberValidation(): string
    {
        $validNumbers = $this->authService->getAllPrefixes();


        $str = "required|numeric|regex_match[/^(";
        $str .= implode('|', $validNumbers);
        $str .= ")/]";
        return $str;
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
        if ($user['roleId'] == 1) {
            return redirect()->to('/admin');
        }
        return redirect()->to('/user');
    }
}
