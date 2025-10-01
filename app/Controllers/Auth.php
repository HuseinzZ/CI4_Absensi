<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    protected $usersModel;

    public function __construct()
    {
        // Inisialisasi Model
        $this->usersModel = model(UsersModel::class);
    }

    public function index()
    {
        $session = service('session');
        $request = service('request');

        $data = [
            'title'      => 'Login',
            'validation' => service('validation'),
        ];

        // Cek jika sudah login, redirect sesuai role
        if ($session->get('username')) {
            switch ($session->get('role_id')) {
                case 1:
                    return redirect()->to(site_url('admin'));
                case 2:
                    return redirect()->to(site_url('profile'));
            }
        }

        $rules = [
            'username' => 'required|trim',
            'password' => 'required|trim',
        ];

        // Jalankan proses login hanya jika POST request & validasi lolos
        if ($request->is('post') && $this->validate($rules)) {
            return $this->_login();
        }

        // Tampilkan form login
        return view('templates/auth_header', $data)
            . view('auth/index')
            . view('templates/auth_footer');
    }

    private function _login()
    {
        $session = service('session');
        $request = service('request');

        $username = $request->getPost('username');
        $password = $request->getPost('password');

        $user = $this->usersModel->getByUsername($username);

        if ($user) {
            if (password_verify($password, $user['password'])) {

                // Data Session yang disetel saat login berhasil
                $userData = [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'role_id'  => $user['role_id'],
                ];
                $session->set($userData);

                // Ambil URL redirect yang disimpan oleh Filter
                if ($redirectUrl = $session->getFlashdata('redirect_url')) {
                    return redirect()->to($redirectUrl);
                }

                // Redirect default berdasarkan role
                switch ($user['role_id']) {
                    case 1:
                        return redirect()->to(site_url('admin'));
                    case 2:
                        return redirect()->to(site_url('profile'));
                    default:
                        return redirect()->to(site_url('auth/blocked'));
                }
            }

            $session->setFlashdata('error', 'Wrong password!');
            return redirect()->to(site_url('auth'))->withInput();
        }

        $session->setFlashdata('error', 'Username not found!');
        return redirect()->to(site_url('auth'))->withInput();
    }

    public function logout()
    {
        $session = service('session');
        // Hapus kunci session yang disetel saat login
        $session->remove(['id', 'username', 'role_id']);
        $session->setFlashdata('success', 'You have been logged out!');
        return redirect()->to(site_url('auth'));
    }

    public function blocked()
    {
        return view('auth/blocked', ['title' => 'Access Blocked']);
    }
}
