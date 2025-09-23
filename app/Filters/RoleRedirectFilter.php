<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleRedirectFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth');
        }
        
        // Check current URL path
        $uri = service('uri');
        $segment = $uri->getSegment(1);
        
        // Get user role
        $role = session()->get('role');
        
        // Check if user is accessing area allowed for their role
        if ($segment) {
            if ($segment == 'admin' && $role != 'admin') {
                return redirect()->to('/redirect');
            } else if ($segment == 'pemilik' && $role != 'pemilik') {
                return redirect()->to('/redirect');
            } else if ($segment == 'pelanggan' && $role != 'pelanggan') {
                return redirect()->to('/redirect');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}