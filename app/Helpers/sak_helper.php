<?php

use Config\Services;
use Config\Database;
use CodeIgniter\HTTP\RedirectResponse;

if (!function_exists('is_logged_in')) {
    /**
     * Memastikan pengguna sudah login dan memiliki akses ke halaman yang diminta.
     *
     * @return RedirectResponse|void
     */
    function is_logged_in()
    {
        // Ambil service yang dibutuhkan
        $session = Services::session();
        $db      = Database::connect();
        $uri     = Services::uri();

        // 1. Cek Login
        if (!$session->get('username')) {
            // Simpan URL yang diminta agar bisa diarahkan kembali setelah login
            $session->setFlashdata('redirect_url', current_url());
            return redirect()->to(site_url('auth'));
        }

        // 2. Cek Akses
        $role_id = $session->get('role_id');
        $menu    = $uri->getSegment(1); // Ambil segmen pertama dari URL

        // Cari ID Menu
        $menu_id = $db->table('user_menu')
            ->select('id')
            ->where('menu', $menu)
            ->get()
            ->getRow('id') ?? 0;

        // Cek apakah role_id punya akses ke menu_id
        $userAccess = $db->table('user_access')
            ->where([
                'role_id' => $role_id,
                'menu_id' => $menu_id,
            ])
            ->countAllResults();

        if ($userAccess < 1) {
            return redirect()->to(site_url('auth/blocked'));
        }
    }
}
