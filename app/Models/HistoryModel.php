<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoryModel extends Model
{
    protected $table      = 'attendance'; // Model ini bekerja pada tabel attendance
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // Properti allowedFields (diabaikan karena ini adalah model read-only)

    /**
     * Mengambil riwayat kehadiran berdasarkan employee_id.
     * Menggantikan get_where() dengan where() dan findAll().
     * * @param int $employee_id
     * @return array
     */
    public function getByEmployee($employee_id)
    {
        return $this->where('employee_id', $employee_id)
            ->orderBy('attendance_date', 'DESC')
            ->findAll();
    }
}
