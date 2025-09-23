<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) { // Pastikan ini konsisten dengan session yang di-set saat login
            // Check if remember me cookie exists
            $rememberToken = get_cookie('remember_token');
            
            if ($rememberToken) {
                // Attempt to log in with remember token
                $email = base64_decode($rememberToken);
                $userModel = new \App\Models\UserModel();
                $user = $userModel->where('email', $email)->first();
                
                if ($user) {
                    // Set session data
                    $sessionData = [
                        'user_id' => $user['id'],
                        'nama' => $user['nama_lengkap'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'logged_in' => true // Konsisten dengan Auth.php
                    ];
                    
                    session()->set($sessionData);
                    
                    // Continue with the request
                    return;
                }
            }
            
            // If not logged in and no valid remember token, redirect to login page
            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}