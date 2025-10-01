<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    // Tidak perlu primaryKey/table karena Model ini hanya untuk reporting (join)
    protected $db;

    public function __construct()
    {
        parent::__construct();
        // Akses instance database yang terhubung (otomatis tersedia, tetapi didefinisikan untuk kejelasan)
        $this->db = \Config\Database::connect();
    }

    /**
     * Fungsi ini untuk mendapatkan RINCIAN harian kehadiran karyawan.
     * @param string $start_date
     * @param string $end_date
     * @param int|string|null $employee_id
     * @return array
     */
    public function getAttendanceReport($start_date, $end_date, $employee_id = null)
    {
        $builder = $this->db->table('attendance a')
            ->select('a.*, e.name AS employee_name, p.name AS position_name')
            ->join('employee e', 'a.employee_id = e.id', 'inner')
            ->join('position p', 'e.position_id = p.id', 'left');

        // Filter berdasarkan rentang tanggal
        $builder->where('a.attendance_date >=', $start_date);
        $builder->where('a.attendance_date <=', $end_date);

        // Filter karyawan
        if (!empty($employee_id) && $employee_id !== 'all') {
            $builder->where('a.employee_id', $employee_id);
        }

        // Urutkan
        $builder->orderBy('a.attendance_date', 'ASC')
            ->orderBy('e.name', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Fungsi utama untuk mendapatkan RINGKASAN total kehadiran karyawan.
     * @param string $start_date
     * @param string $end_date
     * @param int|string|null $employee_id
     * @return array
     */
    public function getAttendanceSummary($start_date, $end_date, $employee_id = null)
    {
        $builder = $this->db->table('employee e')
            ->select('e.id AS employee_id, e.name AS employee_name, p.name AS position_name, COUNT(a.id) AS total_hadir');

        // LEFT JOIN dengan KONDISI pada klausa ON
        // Di CI4, kita menggunakan string untuk kondisi JOIN yang kompleks pada rentang tanggal.
        $join_condition = 'a.employee_id = e.id AND a.attendance_date >= "' . $start_date . '" AND a.attendance_date <= "' . $end_date . '"';
        $builder->join('attendance a', $join_condition, 'left');

        // Filter karyawan
        if (!empty($employee_id) && $employee_id !== 'all') {
            $builder->where('e.id', $employee_id);
        }

        // JOIN ke tabel position
        $builder->join('position p', 'e.position_id = p.id', 'left');

        // Grouping (harus mencakup semua kolom SELECT non-agregasi)
        $builder->groupBy('e.id, e.name, p.name, p.id');

        // Urutkan hasil
        $builder->orderBy('e.name', 'ASC');

        return $builder->get()->getResultArray();
    }
}
