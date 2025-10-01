<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\UsersModel;
use App\Models\MenuModel;

class Attendance extends BaseController
{
    protected $attendanceModel;
    protected $usersModel;
    protected $menuModel;
    protected $session;
    protected $request;

    public function __construct()
    {
        $this->attendanceModel = model(AttendanceModel::class);
        $this->usersModel = model(UsersModel::class);
        $this->menuModel = model(MenuModel::class);
        $this->session = service('session');
        $this->request = service('request');

        // Timezone diatur. Di CI4, ini sebaiknya diatur di Config/App.php
        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Helper untuk memuat data menu, akun, dan template.
     * Mengembalikan RedirectResponse jika user belum login.
     */
    private function loadTemplateData($title)
    {
        $d['title'] = $title;
        $username = $this->session->get('username');
        $role_id = $this->session->get('role_id');

        // Lapisan pelindung jika session hilang (Meskipun dilindungi Filter)
        if (is_null($username) || is_null($role_id)) {
            return redirect()->to(site_url('auth'));
        }

        // Memuat data akun, menu, dan submenu
        $d['account'] = $this->usersModel->getByUsernameWithEmployeeData($username);
        // Cast (int) digunakan untuk menjamin tipe data
        $d['menu'] = $this->menuModel->getMenuByRole((int)$role_id);

        $d['submenus'] = [];
        foreach ($d['menu'] as $mn) {
            $d['submenus'][$mn['id']] = $this->menuModel->getSubMenuByMenuId($mn['id']);
        }

        return $d;
    }


    public function index()
    {
        $d = $this->loadTemplateData('Attendance');

        // Tangani jika loadTemplateData mengembalikan objek Redirect (pengalihan)
        if ($d instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $d;
        }

        $username = $this->session->get('username');
        $user = $this->usersModel->getByUsernameWithEmployeeData($username);
        $today = date('Y-m-d');

        // Ambil data kehadiran
        $d['attendance'] = $this->attendanceModel->getByEmployeeAndDate($user['employee_id'], $today);
        $d['status'] = $this->attendanceModel->getStatus($user['employee_id'], $today);

        // Render View CI4
        return view('templates/header', $d)
            . view('templates/sidebar', $d)
            . view('templates/topbar')
            . view('attendance/index', $d)
            . view('templates/footer');
    }

    public function do_attendance()
    {
        $username = $this->session->get('username');
        $user = $this->usersModel->getByUsernameWithEmployeeData($username);
        $today = date('Y-m-d');
        $attendance = $this->attendanceModel->getByEmployeeAndDate($user['employee_id'], $today);
        $currentTime = date('H:i:s');
        $employeeId = $user['employee_id'];

        // Ambil data koordinat dari POST request CI4
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');

        // Aturan jam kerja
        $minCheckin   = "07:00:00";
        $workStart    = "08:00:00";
        $maxCheckin   = "24:00:00";
        $minCheckout  = "16:30:00";
        $workEnd      = "17:00:00";
        $maxCheckout  = "24:00:00";

        // --- PERBAIKAN ERROR: Mengakses Session via $this->session ---
        $setFlash = function ($type, $message) {
            $this->session->setFlashdata('message', '<div class="alert alert-' . $type . '">' . $message . '</div>');
        };
        // -----------------------------------------------------------


        if (!$attendance) {
            // === Check-in ===
            if ($currentTime < $minCheckin) {
                $setFlash('danger', 'Check-in only opens at ' . $minCheckin);
                return redirect()->to(site_url('attendance'));
            }

            if ($currentTime > $maxCheckin) {
                $setFlash('danger', 'You cannot check-in after ' . $maxCheckin);
                return redirect()->to(site_url('attendance'));
            }

            // Tentukan status hadir / telat
            $status_in = ($currentTime > $workStart) ? 'Late' : 'Present';

            $data = [
                'employee_id'     => $employeeId,
                'attendance_date' => $today,
                'check_in'        => $currentTime,
                'status_in'       => $status_in,
                'latitude'        => $latitude,
                'longitude'       => $longitude,
            ];

            $this->attendanceModel->insert($data);
            $setFlash('success', 'Check-in successful at ' . $currentTime . ' | Status: ' . $status_in);
        } elseif (!$attendance['check_out']) {
            // === Check-out ===
            if ($currentTime < $minCheckout) {
                $setFlash('danger', 'Check-out only opens at ' . $minCheckout);
                return redirect()->to(site_url('attendance'));
            }

            if ($currentTime > $maxCheckout) {
                $setFlash('danger', 'You cannot check-out after ' . $maxCheckout);
                return redirect()->to(site_url('attendance'));
            }

            $status_out = ($currentTime >= $workEnd) ? 'On Time' : 'Left Early';

            $data = [
                'check_out'  => $currentTime,
                'status_out' => $status_out,
            ];

            $this->attendanceModel->update($attendance['id'], $data);
            $setFlash('success', 'Check-out successful at ' . $currentTime . ' | Status: ' . $status_out);
        } else {
            // Sudah check-in & check-out
            $setFlash('warning', 'You have already checked in and out today.');
        }

        return redirect()->to(site_url('attendance'));
    }
}
