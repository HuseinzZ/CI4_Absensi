<?php

namespace App\Controllers;

// Import Models dan Services
use App\Models\UsersModel;
use App\Models\MenuModel;
use App\Models\ReportModel;
use App\Models\EmployeeModel;

class Report extends BaseController
{
    protected $usersModel;
    protected $menuModel;
    protected $reportModel;
    protected $employeeModel;
    protected $session;
    protected $request;

    public function __construct()
    {
        // Inisialisasi Models dan Services CI4
        $this->usersModel = model(UsersModel::class);
        $this->menuModel = model(MenuModel::class);
        $this->reportModel = model(ReportModel::class);
        $this->employeeModel = model(EmployeeModel::class);

        $this->session = service('session');
        $this->request = service('request');
    }

    public function index()
    {
        // --- 1. Pemuatan Data Session & Akun ---
        $d['title'] = 'Print Report';
        $username = $this->session->get('username');
        $role_id = $this->session->get('role_id');

        // Lapisan pelindung (jika session hilang, Filter seharusnya menangani ini)
        if (is_null($username) || is_null($role_id)) {
            return redirect()->to(site_url('auth'));
        }

        // Ambil data user lengkap
        $d['account'] = $this->usersModel->getByUsernameWithEmployeeData($username);

        // --- 2. Pemuatan Data Menu ---
        $d['menu'] = $this->menuModel->getMenuByRole((int)$role_id);
        $d['submenus'] = [];
        foreach ($d['menu'] as $mn) {
            $d['submenus'][$mn['id']] = $this->menuModel->getSubMenuByMenuId($mn['id']);
        }

        // --- 3. Data Default dan Form ---
        $d['all_employees'] = $this->usersModel->getEmployees();
        $d['summary_data'] = null;
        $d['start_date'] = '';
        $d['end_date'] = '';
        $d['selected_employee_id'] = 'all';
        $d['validation'] = service('validation');

        // --- Aturan Validasi CI4 ---
        $rules = [
            'start_date'  => 'required|valid_date',
            'end_date'    => 'required|valid_date',
            'employee_id' => 'required',
        ];

        // --- Proses Form POST ---
        if ($this->request->getPost() && $this->validate($rules)) {

            $start_date = $this->request->getPost('start_date');
            $end_date = $this->request->getPost('end_date');
            $employee_id = $this->request->getPost('employee_id');

            // Memanggil Model Laporan
            $d['summary_data'] = $this->reportModel->getAttendanceSummary($start_date, $end_date, $employee_id);
            $d['start_date'] = $start_date;
            $d['end_date'] = $end_date;
            $d['selected_employee_id'] = $employee_id;
        }

        return view('templates/table_header', $d)
            . view('templates/sidebar', $d)
            . view('templates/topbar')
            . view('report/index', $d)
            . view('templates/table_footer');
    }

    public function print_report()
    {
        // Mengambil input menggunakan service Request CI4 (GET query params)
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $employee_id = $this->request->getGet('employee_id');

        // Validasi dasar
        if (empty($start_date) || empty($end_date)) {
            $this->session->setFlashdata('message', '<div class="alert alert-danger">Please specify a valid date range.</div>');
            return redirect()->to(site_url('report'));
        }

        $d['summary_data'] = $this->reportModel->getAttendanceSummary($start_date, $end_date, $employee_id);
        $d['selected_employee_name'] = 'Semua Karyawan';

        if (!empty($employee_id) && $employee_id !== 'all') {
            $employee_data = $this->employeeModel->getById($employee_id);
            if ($employee_data) {
                $d['selected_employee_name'] = $employee_data['name'];
            }
        }

        $d['start_date'] = $start_date;
        $d['end_date'] = $end_date;
        $d['title'] = 'Attendance Report';

        // Memuat View cetak (biasanya tanpa template header/footer)
        return view('report/print_attendance', $d);
    }
}
