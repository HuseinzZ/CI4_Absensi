<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        // Pemeriksaan login menggunakan kunci 'username' yang disetel saat login
        if (! $session->get('username')) {

            // Simpan URL yang diminta agar dapat dikembalikan setelah login berhasil
            $session->setFlashdata('redirect_url', current_url());

            // Alihkan ke halaman LOGIN
            return redirect()->to(site_url('auth'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosong
    }
}
