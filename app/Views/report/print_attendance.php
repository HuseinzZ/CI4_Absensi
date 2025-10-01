<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title); ?></title>
    <link href="<?= site_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <style>
        body {
            font-size: 10pt;
            padding: 20px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: top;
            padding: 5px 0;
        }

        .header-table .right-col {
            width: 35%;
        }

        .title-box {
            border: 1px solid #000;
            text-align: center;
            padding: 5px;
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
            font-size: 10pt;
            text-align: center;
        }

        .table-sm td:nth-child(2) {
            text-align: left;
        }

        .footer-ttd-table {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }

        .footer-ttd-table td {
            width: 33%;
            padding: 0;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container-fluid">
        <table class="header-table">
            <tr>
                <td style="width: 65%;">
                    <div style="font-weight: bold; font-size: 14pt;">TOKO KURMA AYYUWA DAN AZAM</div>
                    <div style="font-size: 8pt; margin-top: 5px;">
                        Ayyuwa : Jl. Pahlawan No. 46 Bandung <br>
                        Azam : Jl. Raya Gadobangkong No. 55, Ngamprah, Bandung Barat
                    </div>
                </td>
                <td class="right-col">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 60%; font-weight: bold; text-align: right;">
                                LAPORAN RINGKASAN ABSENSI
                            </td>
                            <td style="width: 40%;">
                                <div class="title-box">LAPORAN ABSENSI</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 10px;"></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Periode</td>
                            <td style="border-bottom: 1px solid #000;">
                                <?= date('d/m/Y', strtotime(esc($start_date))); ?> s/d
                                <?= date('d/m/Y', strtotime(esc($end_date))); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Filter Pegawai</td>
                            <td style="border-bottom: 1px solid #000;">
                                <?= esc($selected_employee_name ?? 'Semua Karyawan'); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="section-title">RINGKASAN JUMLAH KEHADIRAN PEGAWAI</div>

        <table class="table table-bordered table-sm" style="margin-bottom: 30px;">
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 40%;">Nama Pegawai</th>
                    <th style="width: 30%;">Jabatan</th>
                    <th style="width: 27%;">Total Kehadiran (Hari)</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                // Menggunakan isset dan memastikan $summary_data adalah array/iterable
                if (isset($summary_data) && is_array($summary_data) && $summary_data):
                    foreach ($summary_data as $row): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= esc($row['employee_name']); ?></td>
                            <td><?= esc($row['position_name']) ?: '-'; ?></td>
                            <td style="font-weight: bold;"><?= esc($row['total_hadir']); ?></td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Tidak ada ringkasan kehadiran ditemukan untuk periode ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="section-title" style="border: none; margin-bottom: 5px; padding-bottom: 0;">
            Keterangan / Catatan:
        </div>
        <div style="border: 1px solid #000; height: 100px; padding: 5px; width: 50%;"></div>

        <table class="footer-ttd-table">
            <tr>
                <td>Disiapkan Oleh,</td>
                <td>Diketahui Oleh,</td>
                <td>Penerima/Pegawai,</td>
            </tr>
            <tr>
                <td style="padding-top: 60px;">
                    <hr style="border-top: 1px solid #000; width: 80%; margin: 0 auto;">
                </td>
                <td style="padding-top: 60px;">
                    <hr style="border-top: 1px solid #000; width: 80%; margin: 0 auto;">
                </td>
                <td style="padding-top: 60px;">
                    <hr style="border-top: 1px solid #000; width: 80%; margin: 0 auto;">
                </td>
            </tr>
            <tr>
                <td>Tgl.</td>
                <td>Tgl.</td>
                <td>Tgl.</td>
            </tr>
        </table>

        <div class="no-print mt-5 text-center">
            <button onclick="window.print()" class="btn btn-primary btn-sm">Print Again</button>
            <button onclick="window.close()" class="btn btn-secondary btn-sm">Close</button>
        </div>
    </div>
</body>

</html>