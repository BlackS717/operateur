<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\AuthService;

class AuthController extends BaseController
{

    protected $authService;
    public function __construct(){
        $this->authService = service('authService');
    }
    public function index(): string
    {
        return view('auth/login');
    }

    public function buildNumberValidation(): string{
        $validNumbers = $this->authService->getAllPrefixes();


        $str = "required|numeric|regex_match[/^(";
        $str.= implode('|', $validNumbers);
        $str.= ")/]";
        return $str;
    }
    
    public function authenticate(): RedirectResponse{

        $rules = [
            'phone' => $this->buildNumberValidation(),
        ];

        if(!$this->validate($rules)){
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        








        return redirect()->back()->withInput()->with('reports', ['phone' => 'login successful']);
    }
}
