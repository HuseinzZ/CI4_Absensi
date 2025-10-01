<?php

namespace App\Controllers;

// Import semua Model yang dibutuhkan
use App\Models\UsersModel;
use App\Models\MenuModel;
use App\Models\HistoryModel;

class History extends BaseController
{
    protected $usersModel;
    protected $menuModel;
    protected $historyModel;
    protected $session;

    public function __construct()
    {
        // Inisialisasi Models CI4 dan Service Session
        $this->usersModel = model(UsersModel::class);
        $this->menuModel = model(MenuModel::class);
        $this->historyModel = model(HistoryModel::class);
        $this->session = service('session');

        // Timezone diatur.
        date_default_timezone_set('Asia/Jakarta');
    }


    public function index()
    {
        // --- 1. Pemuatan Data Session & Akun ---
        $d['title'] = 'Attendance History';
        $username = $this->session->get('username');
        $role_id = $this->session->get('role_id');

        // Lapisan pelindung (jika session hilang, Filter seharusnya menangani ini)
        if (is_null($username) || is_null($role_id)) {
            return redirect()->to(site_url('auth'));
        }

        // Ambil data user lengkap
        $user = $this->usersModel->getByUsernameWithEmployeeData($username);
        $d['account'] = $user;

        // --- 2. Pemuatan Data Menu ---
        // Cast (int) digunakan untuk menjamin tipe data
        $d['menu'] = $this->menuModel->getMenuByRole((int)$role_id);

        // Ambil data submenu
        $d['submenus'] = [];
        foreach ($d['menu'] as $mn) {
            $d['submenus'][$mn['id']] = $this->menuModel->getSubMenuByMenuId($mn['id']);
        }

        // --- 3. Pemuatan Data Inti (History) ---
        // Asumsi HistoryModel memiliki method getByEmployee()
        $d['history'] = $this->historyModel->getByEmployee($user['id']);

        // --- 4. Render View CI4 ---
        return view('templates/table_header', $d)
            . view('templates/sidebar', $d)
            . view('templates/topbar')
            . view('history/index', $d)
            . view('templates/table_footer');
    }
}
