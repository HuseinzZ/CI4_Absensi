<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    /**
     * Mengambil jumlah total data untuk card summary di dashboard.
     * @return array
     */
    public function getDataForDashboard(): array
    {
        $d = [];

        // Jumlah data employee
        $d['c_employee'] = $this->db->table('employee')->countAllResults();

        // Jumlah data position
        $d['c_position'] = $this->db->table('position')->countAllResults();

        // Jumlah data users
        $d['c_users'] = $this->db->table('users')->countAllResults();

        // Jumlah attendance hari ini
        $today_date = date('Y-m-d');
        $d['c_attendance_today'] = $this->db->table('attendance')
            ->where('attendance_date', $today_date)
            ->countAllResults();

        return $d;
    }

    /**
     * Hitung jumlah kehadiran (check-in) per bulan di tahun berjalan untuk Area Chart.
     * @return array
     */
    public function getMonthlyAttendanceCount(): array
    {
        $year = date('Y');

        $query = $this->db->table('attendance')
            ->select("MONTH(attendance_date) as month, COUNT(id) as total")
            ->where("YEAR(attendance_date)", $year)
            ->where("check_in IS NOT NULL")
            ->groupBy("MONTH(attendance_date)")
            ->get();

        // Inisialisasi hasil untuk 12 bulan dengan nilai 0
        $results = array_fill(1, 12, 0);

        foreach ($query->getResultArray() as $row) {
            $results[(int)$row['month']] = (int)$row['total'];
        }

        return array_values($results);
    }

    /**
     * Hitung jumlah dan persentase kehadiran berdasarkan status_in (Present atau Late) untuk Pie Chart.
     * @return array
     */
    public function getAttendanceStatusCounts(): array
    {
        $query = $this->db->table('attendance')
            ->select("status_in, COUNT(id) as total")
            ->where("check_in IS NOT NULL")
            ->groupBy("status_in")
            ->get();

        $present_late_counts = ['Present' => 0, 'Late' => 0];
        $total_all_attendance = 0;

        foreach ($query->getResultArray() as $row) {
            if (isset($present_late_counts[$row['status_in']])) {
                $present_late_counts[$row['status_in']] = (int)$row['total'];
            }
            $total_all_attendance += (int)$row['total'];
        }

        // --- Perhitungan Persentase ---
        if ($total_all_attendance === 0) {
            $percentages = ['Present' => 0, 'Late' => 0];
        } else {
            $percentages = [
                'Present' => (int) round(($present_late_counts['Present'] / $total_all_attendance) * 100),
                'Late'    => (int) round(($present_late_counts['Late'] / $total_all_attendance) * 100)
            ];

            // Penyesuaian total persentase agar selalu 100% karena pembulatan
            $total_percentage = $percentages['Present'] + $percentages['Late'];
            if ($total_percentage !== 100) {
                $percentages['Present'] += (100 - $total_percentage);
            }
        }

        return [
            'counts'               => $present_late_counts,
            'percentages'          => $percentages,
            'total_all_attendance' => $total_all_attendance,
        ];
    }
}
