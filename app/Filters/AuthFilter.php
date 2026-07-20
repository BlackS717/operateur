<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $mode = $arguments[0] ?? 'client';

        if ($mode === 'admin') {
            if (!session()->get('operateurId')) {
                return redirect()->to('/admin/login')->with('reports', ['Veuillez vous connecter.']);
            }
            return;
        }

        if (!session()->get('userId')) {
            return redirect()->to('/')->with('reports', ['Veuillez vous connecter.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
