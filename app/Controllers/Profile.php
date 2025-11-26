<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Ensure user is logged in
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/404');
        }

        $data = [
            'title' => 'Profil Saya',
            'user' => $user,
        ];

        return $this->render('profile/index', $data);
    }

    public function update()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth');
        }

        $userId = $this->session->get('user_id');

        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'no_telepon' => 'permit_empty|numeric|min_length[6]',
            'alamat' => 'permit_empty|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'alamat' => $this->request->getPost('alamat'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->userModel->update($userId, $data);

        // Update session values for name and email
        $this->session->set('nama', $data['nama_lengkap']);
        $this->session->set('email', $data['email']);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePasswordForm()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth');
        }

        $data = ['title' => 'Ubah Kata Sandi'];
        return $this->render('profile/change_password', $data);
    }

    public function changePassword()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth');
        }

        $userId = $this->session->get('user_id');
        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        $user = $this->userModel->find($userId);
        if (!$user || !password_verify($oldPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Kata sandi lama salah.');
        }

        if (strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Kata sandi baru harus minimal 6 karakter.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Konfirmasi kata sandi tidak cocok.');
        }

        $this->userModel->update($userId, ['password' => password_hash($newPassword, PASSWORD_DEFAULT), 'updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/profile')->with('success', 'Kata sandi berhasil diubah.');
    }
}

