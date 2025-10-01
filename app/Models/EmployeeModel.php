<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table      = 'employee';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['name', 'email', 'gender', 'position_id', 'birth_date', 'hire_date', 'image', 'created_at', 'updated_at'];

    /**
     * Mengambil semua data karyawan beserta nama posisi.
     * @return array
     */
    public function getAll()
    {
        return $this->select('employee.*, position.name AS position_name')
            ->join('position', 'position.id = employee.position_id', 'left')
            ->findAll();
    }

    /**
     * Mengambil data karyawan berdasarkan ID beserta nama posisi.
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        return $this->select('employee.*, position.name AS position_name')
            ->join('position', 'position.id = employee.position_id', 'left')
            ->where('employee.id', $id)
            ->first();
    }

    /**
     * Menghapus data karyawan beserta data user yang terhubung (Menggunakan Transaksi CI4).
     * @param int $id
     * @return bool
     */
    public function deleteWithUser($id)
    {
        $this->db->transBegin(); // Mulai transaksi

        // Hapus data user yang terhubung
        $this->db->table('users')->delete(['employee_id' => $id]);

        // Hapus data karyawan (menggunakan metode bawaan Model)
        $this->delete($id);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback(); // Batalkan jika ada yang gagal
            return false;
        } else {
            $this->db->transCommit(); // Simpan jika sukses
            return true;
        }
    }

    /**
     * Mengambil data karyawan berdasarkan email.
     * @param string $email
     * @return array|null
     */
    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    // Metode insert, update, dan delete sederhana menggunakan fungsi bawaan CI4 yang diwarisi.
}
