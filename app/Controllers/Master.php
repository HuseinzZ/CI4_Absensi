<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\EmployeeModel;
use App\Models\PositionModel;
use App\Models\UsersModel;
use App\Models\MenuModel;

use CodeIgniter\Controller;

class Master extends BaseController
{
    // Properti untuk Dependency Injection Model
    protected $attendanceModel;
    protected $employeeModel;
    protected $positionModel;
    protected $usersModel;
    protected $menuModel;

    protected $session;

    public function __construct()
    {
        $this->attendanceModel = model(AttendanceModel::class);
        $this->employeeModel   = model(EmployeeModel::class);
        $this->positionModel   = model(PositionModel::class);
        $this->usersModel      = model(UsersModel::class);
        $this->menuModel       = model(MenuModel::class);

        // Inisialisasi service session
        $this->session = \Config\Services::session();
    }

    /**
     * Helper untuk memuat data template/menu (Menggantikan duplikasi kode)
     */
    private function loadTemplateData($title)
    {
        $d = [];
        $d['title'] = $title;
        $role_id = $this->session->get('role_id');
        $username = $this->session->get('username');

        $d['account'] = $this->usersModel->getByUsernameWithEmployeeData($username);
        $d['menu'] = $this->menuModel->getMenuByRole($role_id);
        $d['submenus'] = [];
        foreach ($d['menu'] as $mn) {
            $d['submenus'][$mn['id']] = $this->menuModel->getSubMenuByMenuId($mn['id']);
        }

        return $d;
    }

    /**
     * CI4: Render View (Menggantikan $this->load->view)
     */
    private function renderView($viewName, $data)
    {
        echo view('templates/table_header', $data);
        echo view('templates/sidebar', $data);
        echo view('templates/topbar');
        echo view($viewName, $data);
        echo view('templates/table_footer');
    }

    // ===============================================
    //                  MASTER EMPLOYEE
    // ===============================================

    // Master employee - Tampilkan daftar employee (master/index)
    public function index()
    {
        $d = $this->loadTemplateData('Employee');
        $d['employee'] = $this->employeeModel->getAll();
        $this->renderView('master/employee/index', $d);
    }

    // Add employee - Tampilkan form dan proses tambah data (master/a_employee)
    public function a_employee()
    {
        $d = $this->loadTemplateData('Add Employee');
        $d['positions'] = $this->positionModel->getAll();

        // CI4: Proses POST Request dan Validasi
        if ($this->request->getPost() && $this->validate($this->validationRules('employee', false))) {

            $data = [
                'name'        => $this->request->getPost('emp_name'),
                'email'       => $this->request->getPost('emp_email'),
                'gender'      => $this->request->getPost('emp_gender'),
                'position_id' => $this->request->getPost('emp_position_id'),
                'birth_date'  => $this->request->getPost('emp_birth_date'),
                'hire_date'   => $this->request->getPost('emp_hire_date'),
                'image'       => 'default.jpg',
            ];

            // Penanganan Upload File CI4
            $file = $this->request->getFile('emp_image');
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $newName = $file->getRandomName();

                // Pindahkan ke direktori (FCPATH adalah folder 'public/')
                if ($file->move(FCPATH . 'assets/img/profile/', $newName)) {
                    $data['image'] = $newName;
                } else {
                    $this->session->setFlashdata('message', '<div class="alert alert-danger">Error upload: ' . $file->getErrorString() . '</div>');
                    return redirect()->to(site_url('master/a_employee'))->withInput();
                }
            }

            $this->employeeModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success">Employee added successfully!</div>');
            return redirect()->to(site_url('master/'));
        }

        $d['validation'] = \Config\Services::validation(); // Kirim service validation ke View
        $this->renderView('master/employee/a_employee', $d);
    }

    // Edit employee - Tampilkan form dan proses update data (master/e_employee/ID)
    public function e_employee($id = null)
    {
        if (is_null($id)) {
            return redirect()->to(site_url('master'));
        }

        $d = $this->loadTemplateData('Edit Employee');
        $d['employee'] = $this->employeeModel->getById($id);
        $d['positions'] = $this->positionModel->getAll();

        if (empty($d['employee'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Employee ID {$id} not found.");
        }

        // CI4: Lakukan validasi dengan rule is_unique kustom CI4
        if ($this->request->getPost() && $this->validate($this->validationRules('employee', true, $id))) {

            $data = [
                'name'        => $this->request->getPost('emp_name'),
                'email'       => $this->request->getPost('emp_email'),
                'gender'      => $this->request->getPost('emp_gender'),
                'position_id' => $this->request->getPost('emp_position_id'),
                'birth_date'  => $this->request->getPost('emp_birth_date'),
                'hire_date'   => $this->request->getPost('emp_hire_date'),
            ];

            // Penanganan Upload File CI4
            $file = $this->request->getFile('emp_image');
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $old_image = $d['employee']['image'];

                // Hapus gambar lama
                if (!empty($old_image) && $old_image != 'default.jpg') {
                    $imagePath = FCPATH . 'assets/img/profile/' . $old_image;
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // Pindahkan file baru
                $newName = $file->getRandomName();
                if ($file->move(FCPATH . 'assets/img/profile/', $newName)) {
                    $data['image'] = $newName;
                } else {
                    $this->session->setFlashdata('message', '<div class="alert alert-danger">Error upload: ' . $file->getErrorString() . '</div>');
                    return redirect()->to(site_url('master/e_employee/' . $id))->withInput();
                }
            }

            $this->employeeModel->update($id, $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success">Employee updated successfully!</div>');
            return redirect()->to(site_url('master/'));
        }

        $d['validation'] = \Config\Services::validation();
        $this->renderView('master/employee/e_employee', $d);
    }

    // Delete employee - Hapus data (master/d_employee/ID)
    public function d_employee($id)
    {
        $employee_data = $this->employeeModel->getById($id);

        if (!empty($employee_data)) {
            // Hapus file gambar
            if ($employee_data['image'] != 'default.jpg') {
                $imagePath = FCPATH . 'assets/img/profile/' . $employee_data['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Memanggil metode Model CI4 yang menggunakan transaksi
            $this->employeeModel->deleteWithUser($id);
            $this->session->setFlashdata('message', '<div class="alert alert-success">Employee deleted successfully!</div>');
        }

        return redirect()->to(site_url('master/'));
    }

    // ===============================================
    //                  MASTER POSITION
    // ===============================================

    // Master position - Tampilkan daftar position (master/position)
    public function position()
    {
        $d = $this->loadTemplateData('Position');
        $d['position'] = $this->positionModel->getAll();
        $this->renderView('master/position/index', $d);
    }

    // Add position - Tampilkan form dan proses tambah data (master/a_position)
    public function a_position()
    {
        $d = $this->loadTemplateData('Add Position');

        if ($this->request->getPost() && $this->validate($this->validationRules('position', false))) {

            $data = [
                'id' => $this->request->getPost('p_id'),
                'name' => $this->request->getPost('p_name')
            ];

            $exists = $this->positionModel->getById($data['id']);
            if ($exists) {
                // CI4: Pesan error khusus (jika ID tidak auto-increment)
                $this->session->setFlashdata('message', '<div class="alert alert-danger">ID already in use!</div>');
                return redirect()->to(site_url('master/a_position'))->withInput();
            } else {
                $this->positionModel->insert($data);
                $this->session->setFlashdata('message', '<div class="alert alert-success">Position added successfully!</div>');
                return redirect()->to(site_url('master/position'));
            }
        }

        $d['validation'] = \Config\Services::validation();
        $this->renderView('master/position/a_position', $d);
    }

    // Edit position - Tampilkan form dan proses update data (master/e_position/ID)
    public function e_position($id)
    {
        $d = $this->loadTemplateData('Edit Position');
        $d['old_position'] = $this->positionModel->getById($id);

        if ($this->request->getPost() && $this->validate($this->validationRules('position', true))) {
            $data = ['name' => $this->request->getPost('p_name')];
            $this->positionModel->update($id, $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success">Position updated successfully!</div>');
            return redirect()->to(site_url('master/position'));
        }

        $d['validation'] = \Config\Services::validation();
        $this->renderView('master/position/e_position', $d);
    }

    // Delete position - Hapus data (master/d_position/ID)
    public function d_position($id)
    {
        $this->positionModel->delete($id);
        $this->session->setFlashdata('message', '<div class="alert alert-success">Position deleted successfully!</div>');
        return redirect()->to(site_url('master/position'));
    }

    // ===============================================
    //                  MASTER USERS
    // ===============================================

    // Master users - Tampilkan daftar users (master/users)
    public function users()
    {
        $d = $this->loadTemplateData('Users');
        $d['data'] = $this->usersModel->getAllUsersWithEmployeeData();
        $this->renderView('master/users/index', $d);
    }

    // Add users - Tampilkan form dan proses tambah data (master/a_users/ID/POSITION_ID)
    public function a_users($id, $position_id)
    {
        $d = $this->loadTemplateData('Create Account');
        $d['employee_id'] = $id;
        $d['position_id'] = $position_id;

        if ($this->request->getPost() && $this->validate($this->validationRules('users', false))) {

            $username_auto = strtolower($position_id) . str_pad($id, 3, '0', STR_PAD_LEFT);
            $password = $this->request->getPost('u_password');

            $data = [
                'username'      => $username_auto,
                'password'      => password_hash($password, PASSWORD_DEFAULT),
                'employee_id'   => $id,
                // Logika Role: CEO=1, Lainnya=2
                'role_id'       => ($position_id === 'CEO') ? 1 : 2,
            ];

            $existing_user = $this->usersModel->getByUsername($username_auto);
            if ($existing_user) {
                $this->session->setFlashdata('message', '<div class="alert alert-danger">Username already exists.</div>');
                return redirect()->to(site_url('master/users'));
            }

            $this->usersModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success">User added successfully!</div>');
            return redirect()->to(site_url('master/users'));
        }

        $d['validation'] = \Config\Services::validation();
        $this->renderView('master/users/a_users', $d);
    }

    // Edit users - Tampilkan form dan proses update data (master/e_users/USERNAME)
    public function e_users($username)
    {
        $d = $this->loadTemplateData('Edit Account');
        $d['user'] = $this->usersModel->getByUsername($username);

        if ($this->request->getPost() && $this->validate($this->validationRules('users', true))) {

            $password = $this->request->getPost('u_password');
            $data = [
                'password'   => password_hash($password, PASSWORD_DEFAULT),
            ];

            $this->usersModel->updateByUsername($username, $data); // Asumsi metode updateByUsername ada di Model
            $this->session->setFlashdata('message', '<div class="alert alert-success">User updated successfully!</div>');
            return redirect()->to(site_url('master/users'));
        }

        $d['validation'] = \Config\Services::validation();
        $this->renderView('master/users/e_users', $d);
    }

    // Delete users - Hapus data (master/d_users/USERNAME)
    public function d_users($username)
    {
        $this->usersModel->deleteByUsername($username); // Asumsi metode deleteByUsername ada di Model
        $this->session->setFlashdata('message', '<div class="alert alert-success">User deleted successfully!</div>');
        return redirect()->to(site_url('master/users'));
    }

    // ===============================================
    //                  MASTER ATTENDANCE
    // ===============================================

    // Master attendance - Tampilkan daftar absen (master/attendance)
    public function attendance()
    {
        $d = $this->loadTemplateData('Attendance');
        $d['attendance'] = $this->attendanceModel->getAllWithEmployee(); // Asumsi: Metode ini ada di AttendanceModel
        $this->renderView('master/attendance/index', $d);
    }

    // Add attendance - Tampilkan form dan proses tambah data (master/a_attendance)
    public function a_attendance()
    {
        $d = $this->loadTemplateData('Add Attendance');
        $d['employees'] = $this->usersModel->getEmployees(); // Asumsi: Metode ini ada di UsersModel

        if ($this->request->getPost() && $this->validate($this->validationRules('attendance', false))) {
            $data = [
                'employee_id'     => $this->request->getPost('employee_id'),
                'attendance_date' => $this->request->getPost('attendance_date'),
                'check_in'        => $this->request->getPost('check_in'),
                'status_in'       => $this->request->getPost('status_in'),
                'check_out'       => $this->request->getPost('check_out'),
                'status_out'      => $this->request->getPost('status_out'),
                'latitude'        => $this->request->getPost('latitude'),
                'longitude'       => $this->request->getPost('longitude'),
            ];
            $this->attendanceModel->insert($data);
            $this->session->setFlashdata('message', '<div class="alert alert-success">Attendance added successfully!</div>');
            return redirect()->to(site_url('master/attendance'));
        }

        $d['validation'] = \Config\Services::validation();
        $this->renderView('master/attendance/a_attendance', $d);
    }

    // Edit attendance - Tampilkan form dan proses update data (master/e_attendance/ID)
    public function e_attendance($id)
    {
        $d = $this->loadTemplateData('Edit Attendance');
        $d['attendance'] = $this->attendanceModel->getByIdWithEmployeeName($id);
        $d['employees'] = $this->employeeModel->getAll();

        if ($this->request->getPost()) {
            // CI3 tidak menggunakan validasi di sini, jadi di CI4 kita bisa memproses langsung POST atau menambahkan rule validasi
            $data = [
                'employee_id'     => $this->request->getPost('employee_id'),
                'attendance_date' => $this->request->getPost('attendance_date'),
                'check_in'        => $this->request->getPost('check_in'),
                'status_in'       => $this->request->getPost('status_in'),
                'check_out'       => $this->request->getPost('check_out'),
                'status_out'      => $this->request->getPost('status_out'),
                'latitude'        => $this->request->getPost('latitude'),
                'longitude'       => $this->request->getPost('longitude'),
            ];
            $this->attendanceModel->update($id, $data);
            $this->session->setFlashdata('message', '<div class="alert alert-success">Attendance updated successfully!</div>');
            return redirect()->to(site_url('master/attendance'));
        }

        $d['validation'] = \Config\Services::validation();
        $this->renderView('master/attendance/e_attendance', $d);
    }

    // Delete attendance - Hapus data (master/d_attendance/ID)
    public function d_attendance($id)
    {
        $this->attendanceModel->delete($id);
        $this->session->setFlashdata('message', '<div class="alert alert-success">Attendance deleted successfully!</div>');
        return redirect()->to(site_url('master/attendance'));
    }


    // ===============================================
    //                  VALIDATION RULES CI4
    // ===============================================

    /**
     * Aturan validasi CI4
     */
    protected function validationRules($context, $isUpdate, $id = null)
    {
        switch ($context) {
            case 'employee':
                $rules = [
                    'emp_name'        => 'required|trim',
                    'emp_gender'      => 'required',
                    'emp_position_id' => 'required',
                    'emp_birth_date'  => 'required',
                    'emp_hire_date'   => 'required',
                    'emp_image'       => 'max_size[emp_image,2048]|mime_in[emp_image,image/gif,image/jpg,image/jpeg,image/png]',
                ];
                if ($isUpdate) {
                    // is_unique CI4: [table.field,primaryKeyField,primaryKeyValue]
                    $rules['emp_email'] = "required|trim|valid_email|is_unique[employee.email,id,{$id}]";
                } else {
                    $rules['emp_email'] = 'required|trim|valid_email|is_unique[employee.email]';
                }
                return $rules;

            case 'position':
                $rules = [
                    'p_name' => 'required|trim',
                ];
                if (!$isUpdate) {
                    $rules['p_id'] = 'required|trim|alpha_numeric|max_length[3]|is_unique[position.id]';
                }
                return $rules;

            case 'users':
                $rules = [
                    'u_password'  => 'required|trim|min_length[8]|matches[u_password2]',
                    'u_password2' => 'required|trim|matches[u_password]',
                ];
                return $rules;

            case 'attendance':
                $rules = [
                    'employee_id'     => 'required',
                    'attendance_date' => 'required',
                    'check_in'        => 'required',
                    'status_in'       => 'required',
                ];
                return $rules;

            default:
                return [];
        }
    }
}
