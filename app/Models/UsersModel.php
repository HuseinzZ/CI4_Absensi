<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    // Properti dasar Model
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'username',
        'password',
        'role_id',
        'employee_id',
        'created_at', // Diasumsikan kolom ini ada
        'updated_at', // Diasumsikan kolom ini ada
        // 'etc_fields', // Dihapus karena biasanya field ini tidak ada di DB
    ];

    // =====================
    // CRUD Dasar (Gunakan deklarasi sederhana)
    // =====================

    public function getAll()
    {
        return $this->findAll();
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function getByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function updateData($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteData($id)
    {
        return $this->delete($id);
    }

    // =====================
    // Metode Kustom (Hapus Type Hinting di Sini)
    // =====================

    /**
     * Mengambil data pengguna + employee + posisi berdasarkan username.
     */
    public function getByUsernameWithEmployeeData($username)
    {
        return $this->select('users.*, employee.*, position.id AS position_id, position.name AS position_name')
            ->join('employee', 'users.employee_id = employee.id', 'inner')
            ->join('position', 'employee.position_id = position.id', 'left')
            ->where('users.username', $username)
            ->first();
    }

    /**
     * Mengambil daftar semua karyawan dengan posisi + username.
     */
    public function getAllUsersWithEmployeeData()
    {
        return $this->db->table('employee')
            ->select('employee.id AS e_id, position.id AS d_id, users.username AS u_username, employee.name AS e_name')
            ->join('position', 'employee.position_id = position.id', 'inner')
            ->join('users', 'employee.id = users.employee_id', 'left')
            ->orderBy('employee.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Update data pengguna berdasarkan username.
     */
    public function updateByUsername($username, $data)
    {
        return $this->where('username', $username)->set($data)->update();
    }

    /**
     * Hapus data pengguna berdasarkan username.
     */
    public function deleteByUsername($username)
    {
        return $this->where('username', $username)->delete();
    }

    /**
     * Mengambil semua employee (role_id = 2).
     */
    public function getEmployees()
    {
        return $this->db->table('employee')
            ->select('employee.id, employee.name')
            ->join('users', 'employee.id = users.employee_id', 'inner')
            ->where('users.role_id', 2)
            ->orderBy('employee.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Mengambil data employee tunggal berdasarkan ID employee.
     */
    public function getEmployeeDataById($employee_id)
    {
        return $this->db->table('employee')
            ->where('id', $employee_id)
            ->get()
            ->getRowArray();
    }
}
