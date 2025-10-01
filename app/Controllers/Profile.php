<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\MenuModel;

class Profile extends BaseController
{
    protected $usersModel;
    protected $menuModel;

    public function __construct()
    {
        $this->usersModel = model(UsersModel::class);
        $this->menuModel = model(MenuModel::class);
    }

    public function index()
    {
        $session = service('session');

        $d['title'] = 'My Profile';
        $username = $session->get('username');
        $role_id = $session->get('role_id');

        if (is_null($username) || is_null($role_id)) {
            return redirect()->to(site_url('auth'));
        }

        $d['account'] = $this->usersModel->getByUsernameWithEmployeeData($username);
        $d['menu'] = $this->menuModel->getMenuByRole((int)$role_id);

        $d['submenus'] = [];
        foreach ($d['menu'] as $mn) {
            $d['submenus'][$mn['id']] = $this->menuModel->getSubMenuByMenuId($mn['id']);
        }

        return view('templates/header', $d)
            . view('templates/sidebar', $d)
            . view('templates/topbar')
            . view('profile/index', $d)
            . view('templates/footer');
    }
}
