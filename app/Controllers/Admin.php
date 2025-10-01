<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\DashboardModel;
use App\Models\MenuModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Admin extends BaseController
{
    /**
     * Deklarasi properti untuk Model
     */
    protected $usersModel;
    protected $dashboardModel;
    protected $menuModel;

    /**
     * Metode ini dipanggil sebelum metode di controller dijalankan.
     * Ini menggantikan constructor untuk inisialisasi di BaseController.
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        // Panggil initController() milik BaseController
        parent::initController($request, $response, $logger);

        // 2. Inisialisasi Model
        $this->usersModel     = new UsersModel();
        $this->dashboardModel = new DashboardModel();
        $this->menuModel      = new MenuModel();
    }

    /**
     * Halaman Dashboard Admin.
     */
    public function index()
    {
        $session = service('session');

        $data = [
            'title'              => 'Dashboard',
            // Ambil data user
            'account'            => $this->usersModel->getByUsernameWithEmployeeData($session->get('username')),
            // Ambil data Dashboard
            'display'            => $this->dashboardModel->getDataForDashboard(),
            'monthly_attendance' => $this->dashboardModel->getMonthlyAttendanceCount(),
            'attendance_status'  => $this->dashboardModel->getAttendanceStatusCounts(),
        ];

        // Ambil data Menu
        $role_id      = $session->get('role_id');
        $data['menu'] = $this->menuModel->getMenuByRole($role_id);

        // Ambil Submenu
        $data['submenus'] = [];
        foreach ($data['menu'] as $mn) {
            $data['submenus'][$mn['id']] = $this->menuModel->getSubMenuByMenuId($mn['id']);
        }

        // Memuat View
        return view('templates/dashboard_header', $data)
            . view('templates/sidebar', $data)
            . view('templates/topbar', $data)
            . view('admin/index', $data)
            . view('templates/dashboard_footer');
    }
}
