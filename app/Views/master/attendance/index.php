<div class="container-fluid">
    <?php $session = \Config\Services::session(); ?>

    <?php if ($session->getFlashdata('message')) : ?>
        <?= $session->getFlashdata('message'); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Attendance</h6>

            <a href="<?= site_url('master/a_attendance'); ?>" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <span class="text">Add Attendance</span>
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Check-in</th>
                            <th>Status In</th>
                            <th>Check-out</th>
                            <th>Status Out</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($attendance as $row): ?>
                            <tr>
                                <td class="align-middle text-center"><?= $i++; ?></td>
                                <td class="align-middle text-center">
                                    <?= date('d-m-Y', strtotime($row['attendance_date'])); ?>
                                </td>
                                <td class="align-middle"><?= esc($row['employee_name']); ?></td>
                                <td class="align-middle text-center"><?= $row['check_in'] ?: '-'; ?></td>
                                <td class="align-middle text-center"><?= $row['status_in'] ?: '-'; ?></td>
                                <td class="align-middle text-center"><?= $row['check_out'] ?: '-'; ?></td>
                                <td class="align-middle text-center"><?= $row['status_out'] ?: '-'; ?></td>
                                <td class="align-middle text-center">
                                    <?php if ($row['latitude'] && $row['longitude']): ?>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?= esc($row['latitude']); ?>,<?= esc($row['longitude']); ?>"
                                            target="_blank" class="text-primary">
                                            View Location
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="<?= site_url('master/e_attendance/' . $row['id']); ?>"
                                        class="btn btn-warning btn-icon-split btn-sm">
                                        <span class="icon text-white">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                    </a>

                                    <a href="<?= site_url('master/d_attendance/' . $row['id']); ?>"
                                        onclick="return confirm('Deleted Attendance will be lost forever. Still want to delete?');"
                                        class="btn btn-danger btn-icon-split btn-sm ml-2">
                                        <span class="icon text-white">
                                            <i class="fas fa-trash-alt"></i>
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>