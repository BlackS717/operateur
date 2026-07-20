<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function index(): string
    {
        return view('auth/login');        
    }

    public function buildNumberValidation(): string{
        $validNumbers = ['033', '037'];


        $str = "required|numeric|regex_match[/^(";
        $str.= implode('|', $validNumbers);
        $str.= ")]";
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
