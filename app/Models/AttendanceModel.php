<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table      = 'attendance';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status_in',
        'status_out',
        'latitude',
        'longitude',
        'created_at',
        'updated_at'
    ];

    /**
     * Ambil absensi berdasarkan karyawan & tanggal.
     * @param int $employee_id
     * @param string $attendance_date
     * @return array|null
     */
    public function getByEmployeeAndDate($employee_id, $attendance_date)
    {
        return $this->where([
            'employee_id' => $employee_id,
            'attendance_date' => $attendance_date
        ])->first();
    }

    // Metode insert, update, dan delete sederhana diwarisi dari parent Model.

    /**
     * Ambil semua absensi hari ini.
     * @return array
     */
    public function getTodayAll()
    {
        $today = date('Y-m-d');
        return $this->where('attendance_date', $today)->findAll();
    }

    /**
     * Ambil status absensi karyawan tertentu pada tanggal tertentu.
     * @param int $employee_id
     * @param string $attendance_date
     * @return string
     */
    public function getStatus($employee_id, $attendance_date)
    {
        $row = $this->select('check_in, check_out, status_in, status_out')
            ->where('employee_id', $employee_id)
            ->where('attendance_date', $attendance_date)
            ->first();

        if (empty($row)) {
            return 'Absent';
        }

        if (!empty($row['check_out'])) {
            return $row['status_out'];
        } elseif (!empty($row['check_in'])) {
            return $row['status_in'];
        }

        return 'Absent';
    }

    /**
     * Ambil semua absensi + data user (melalui employee).
     * Catatan: Query asli menggunakan users.name, tetapi users tidak join langsung ke attendance.
     * @return array
     */
    public function getAllWithUsers()
    {
        // Menggunakan JOIN ganda (attendance -> employee -> users)
        return $this->db->table('attendance a')
            ->select('a.*, u.username, e.name AS employee_name') // Mengganti users.name dengan employee.name
            ->join('employee e', 'e.id = a.employee_id', 'inner')
            ->join('users u', 'u.employee_id = e.id', 'inner')
            ->orderBy('a.attendance_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil semua absensi + data karyawan (Sesuai dengan getAllWithEmployee)
     * @return array
     */
    public function getAllWithEmployee()
    {
        return $this->select('attendance.*, employee.name AS employee_name, employee.email')
            ->join('employee', 'employee.id = attendance.employee_id')
            ->orderBy('attendance.attendance_date', 'DESC')
            ->findAll();
    }

    /**
     * Ambil absensi berdasarkan ID (menggunakan metode find bawaan).
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        return $this->find($id);
    }

    /**
     * Ambil absensi + nama karyawan berdasarkan ID.
     * @param int $id
     * @return array|null
     */
    public function getByIdWithEmployeeName($id)
    {
        return $this->select('attendance.*, employee.name AS employee_name')
            ->join('employee', 'employee.id = attendance.employee_id')
            ->where('attendance.id', $id)
            ->first();
    }

    /**
     * Ambil laporan absensi berdasarkan rentang tanggal.
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function getAttendanceReport($start_date, $end_date)
    {
        return $this->db->table('attendance a')
            ->select('a.*, e.name AS employee_name, p.name AS position_name')
            ->join('employee e', 'a.employee_id = e.id', 'inner')
            ->join('position p', 'e.position_id = p.id', 'left')
            ->where('a.attendance_date >=', $start_date)
            ->where('a.attendance_date <=', $end_date)
            ->orderBy('a.attendance_date', 'ASC')
            ->orderBy('e.name', 'ASC')
            ->get()
            ->getResultArray();
    }
}
