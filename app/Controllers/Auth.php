<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Display login/register page
        return view('auth/login');
    }

    public function processLogin()
    {
        // Validate form input
        $rules = [
            'email' => 'required|valid_email',
            'password_in' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/auth')->withInput()->with('error', 'Email atau kata sandi tidak valid.');
        }

        // Get form data
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password_in');
        $remember = $this->request->getPost('remember') ? true : false;

        // Check user credentials
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->to('/auth')->with('error', 'Email atau kata sandi salah.');
        }

        // Set session data
        $sessionData = [
            'user_id' => $user['id'],
            'nama' => $user['nama_lengkap'],
            'email' => $user['email'],
            'role' => $user['role'],
            'logged_in' => true
        ];
        
        session()->set($sessionData);

        // Handle remember me functionality
        if ($remember) {
            // Set cookie for remember me - valid for 30 days
            $this->response->setCookie('remember_token', base64_encode($user['email']), 3600 * 24 * 30);
        }

        // Redirect based on role
        return redirect()->to('/redirect');
    }

    public function processRegister()
    {
        // Validate form input
        $rules = [
            'nama' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'no_hp' => 'required|numeric|min_length[10]',
            'alamat' => 'required|min_length[10]'
        ];

        $errors = [
            'email' => [
                'is_unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->to('/auth')->withInput()->with('error', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'nama_lengkap' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'no_telepon' => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
            'role' => 'pelanggan', // Default role is 'pelanggan'
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Save user to database
        $userModel = new UserModel();
        $userModel->insert($data);

        // Set success message and redirect to login page
        return redirect()->to('/auth')->with('success', 'Registrasi berhasil. Silakan login dengan akun baru Anda.');
    }

    public function logout()
    {
        // Clear session
        session()->destroy();
        
        // Remove remember me cookie if exists
        if ($this->request->getCookie('remember_token')) {
            $this->response->deleteCookie('remember_token');
        }
        
        // Redirect to login page
        return redirect()->to('/auth')->with('success', 'Anda telah berhasil keluar.');
    }

    public function redirectByRole()
    {
        // Redirect user based on role
        $role = session()->get('role');
        
        switch ($role) {
            case 'admin':
                return redirect()->to('admin/dashboard');
            case 'pemilik':
                return redirect()->to('pemilik/dashboard');
            case 'pelanggan':
            default:
                return redirect()->to('/'); // Redirect to main page instead of /pelanggan/beranda
        }
    }
}