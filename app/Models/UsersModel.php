<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    // Properti dasar Model
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Set true jika menggunakan soft delete

    protected $allowedFields = [
        'username',
        'password',
        'role_id',
        'employee_id',
        'etc_fields', // pastikan field ini benar-benar ada di DB
    ];

    // =====================
    // CRUD Dasar
    // =====================

    /**
     * Mengambil semua data pengguna dari tabel 'users'.
     */
    public function getAll(): array
    {
        return $this->findAll();
    }

    /**
     * Mengambil data pengguna berdasarkan ID.
     */
    public function getById(int $id): ?array
    {
        return $this->find($id);
    }

    /**
     * Mengambil data pengguna berdasarkan username.
     */
    public function getByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Update data pengguna berdasarkan ID.
     */
    public function updateData(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    /**
     * Hapus data pengguna berdasarkan ID.
     */
    public function deleteData(int $id): bool
    {
        return $this->delete($id);
    }

    // =====================
    // Metode Kustom
    // =====================

    /**
     * Mengambil data pengguna + employee + posisi berdasarkan username.
     */
    public function getByUsernameWithEmployeeData(string $username): ?array
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
    public function getAllUsersWithEmployeeData(): array
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
    public function updateByUsername(string $username, array $data): bool
    {
        return $this->where('username', $username)->set($data)->update();
    }

    /**
     * Hapus data pengguna berdasarkan username.
     */
    public function deleteByUsername(string $username): bool
    {
        return $this->where('username', $username)->delete();
    }

    /**
     * Mengambil semua employee (role_id = 2).
     */
    public function getEmployees(): array
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
    public function getEmployeeDataById(int $employee_id): ?array
    {
        return $this->db->table('employee')
            ->where('id', $employee_id)
            ->get()
            ->getRowArray();
    }
}
