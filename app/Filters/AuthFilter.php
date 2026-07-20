<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userId = session()->get('userId');

        if (!$userId) {
            return redirect()->to('/')->with('reports', ['Veuillez vous connecter.']);
        }

        $role = session()->get('roleId');
        if ($arguments !== null && !in_array((int) $role, array_map('intval', $arguments), true)) {
            return redirect()->to('/')->with('reports', ['Acces non autorise.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
