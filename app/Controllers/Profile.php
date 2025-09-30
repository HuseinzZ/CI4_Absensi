<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\MenuModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Profile extends BaseController
{
    protected $usersModel;
    protected $menuModel;

    /**
     * Inisialisasi Model dan cek akses login.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Cek apakah user sudah login
        is_logged_in();

        // Inisialisasi Model
        $this->usersModel = new UsersModel();
        $this->menuModel  = new MenuModel();
    }

    /**
     * Halaman profil pengguna.
     */
    public function index()
    {
        $session = service('session');

        $data = [
            'title'   => 'My Profile',
            'account' => $this->usersModel->getByUsernameWithEmployeeData($session->get('username')),
        ];

        // Menu & Submenu berdasarkan role
        $roleId      = $session->get('role_id');
        $data['menu'] = $this->menuModel->getMenuByRole($roleId);

        $data['submenus'] = [];
        foreach ($data['menu'] as $mn) {
            $data['submenus'][$mn['id']] = $this->menuModel->getSubMenuByMenuId($mn['id']);
        }

        // Load view dengan layout dashboard
        echo view('templates/header', $data);
        echo view('templates/sidebar', $data);
        echo view('templates/topbar', $data);
        echo view('profile/index', $data);
        echo view('templates/footer');
    }
}
