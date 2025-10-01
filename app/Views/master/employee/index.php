<?php $session = \Config\Services::session(); ?>

<div class="container-fluid">

    <?php if ($session->getFlashdata('message')) : ?>
        <?= $session->getFlashdata('message'); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Employee</h6>

            <a href="<?= site_url('master/a_employee'); ?>" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <span class="text">Add new employee</span>
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Gender</th>
                            <th>Image</th>
                            <th>Birth Date</th>
                            <th>Hire Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        // Variabel $employee harus tersedia dari Master::index()
                        foreach ($employee as $emp): ?>
                            <tr>
                                <td class="align-middle text-center"><?= $i++; ?></td>
                                <td class="align-middle"><?= esc($emp['name']); ?></td>
                                <td class="align-middle"><?= esc($emp['position_name']); ?></td>
                                <td class="align-middle text-center">
                                    <?= ($emp['gender'] === 'M') ? 'Male' : 'Female'; ?>
                                </td>
                                <td class="text-center">
                                    <img src="<?= base_url('assets/img/profile/' . $emp['image']); ?>"
                                        alt="<?= esc($emp['name']); ?>"
                                        class="rounded-circle"
                                        style="width: 70px; height: 70px; object-fit: cover; object-position: top;">
                                </td>
                                <td class="align-middle text-center"><?= date('d-m-Y', strtotime($emp['birth_date'])); ?></td>
                                <td class="align-middle text-center"><?= date('d-m-Y', strtotime($emp['hire_date'])); ?></td>
                                <td class="align-middle text-center">

                                    <a href="<?= site_url('master/e_employee/' . $emp['id']); ?>"
                                        class="btn btn-warning btn-icon-split btn-sm">
                                        <span class="icon text-white"><i class="fas fa-edit"></i></span>
                                    </a>

                                    <a href="<?= site_url('master/d_employee/' . $emp['id']); ?>"
                                        class="btn btn-danger btn-icon-split btn-sm ml-2"
                                        onclick="return confirm('Deleted employee will be lost forever. Still want to delete?')">
                                        <span class="icon text-white"><i class="fas fa-trash-alt"></i></span>
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