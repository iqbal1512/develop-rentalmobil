<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

/**
 * Auth Controller
 * Activity Diagram Login:
 * 1. Buka halaman login -> Sistem tampilkan form
 * 2. Input username & password -> Klik Login
 * 3. Sistem validasi -> Valid: tampilkan dashboard | Tidak: kembali ke form
 * 4. Klik logout -> session destroy -> halaman login
 */
class Auth extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    /** Tampilkan form login */
    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('auth/login');
    }

    /** Proses login */
    public function proses()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->to(base_url('login'))
                             ->with('error', 'Username dan password wajib diisi.');
        }

        $user = $this->userModel->cekLogin($username, $password);

        if ($user) {
            // Set session (Activity Diagram: Tampilkan Dashboard)
            session()->set([
                'logged_in'  => true,
                'id_user'    => $user['id_user'],
                'username'   => $user['username'],
                'nama'       => $user['nama'],
                'role'       => $user['role'],
            ]);
            
            return redirect()->to(base_url('dashboard'))
                             ->with('success', 'Selamat datang, ' . $user['nama'] . '!');
        }

        // Tidak valid: kembali ke form login
        return redirect()->to(base_url('login'))
                         ->with('error', 'Username atau password salah.');
    }

    /** Logout (Activity Diagram: destroy session) */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))
                         ->with('success', 'Anda telah berhasil logout.');
    }
}
