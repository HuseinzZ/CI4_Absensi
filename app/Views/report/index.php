<div class="container-fluid">
    <?php $session = \Config\Services::session(); ?>
    <?php $validation = \Config\Services::validation(); ?>

    <?php if ($session->getFlashdata('message')) : ?>
        <?= $session->getFlashdata('message'); ?>
    <?php endif; ?>

    <div class="card shadow mb-5 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Attendance Report</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('report'); ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="employee_id">Employee</label>
                        <select class="form-control <?= $validation->hasError('employee_id') ? 'is-invalid' : ''; ?>"
                            id="employee_id" name="employee_id" required>

                            <option value="all" <?= (old('employee_id', $selected_employee_id) == 'all') ? 'selected' : ''; ?>>
                                All Employees
                            </option>
                            <?php if (isset($all_employees)): ?>
                                <?php foreach ($all_employees as $employee): ?>
                                    <option value="<?= esc($employee['id']); ?>"
                                        <?= (old('employee_id', $selected_employee_id) == $employee['id']) ? 'selected' : ''; ?>>
                                        <?= esc($employee['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if ($validation->getError('employee_id')) : ?>
                            <small class="text-danger pl-3"><?= $validation->getError('employee_id'); ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="start_date">Start Date</label>
                        <input type="date"
                            class="form-control <?= $validation->hasError('start_date') ? 'is-invalid' : ''; ?>"
                            id="start_date" name="start_date"
                            value="<?= old('start_date', $start_date); ?>" required>
                        <?php if ($validation->getError('start_date')) : ?>
                            <small class="text-danger pl-3"><?= $validation->getError('start_date'); ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="end_date">End Date</label>
                        <input type="date"
                            class="form-control <?= $validation->hasError('end_date') ? 'is-invalid' : ''; ?>"
                            id="end_date" name="end_date"
                            value="<?= old('end_date', $end_date); ?>" required>
                        <?php if ($validation->getError('end_date')) : ?>
                            <small class="text-danger pl-3"><?= $validation->getError('end_date'); ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($summary_data !== null): ?>
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Summary Data (<?= date('d M Y', strtotime(esc($start_date))); ?>
                            to <?= date('d M Y', strtotime(esc($end_date))); ?>)
                        </h6>
                        <a href="<?= site_url('report/print_report?start_date=' . esc($start_date) . '&end_date=' . esc($end_date) . '&employee_id=' . esc($selected_employee_id)); ?>"
                            target="_blank" class="btn btn-success btn-sm">
                            <i class="fas fa-print"></i> Print Report
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                    if ($summary_data): foreach ($summary_data as $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $i++; ?></td>
                                                <td><?= esc($row['employee_name']); ?></td>
                                                <td class="text-center"><?= esc($row['position_name']) ?: '-'; ?></td>
                                                <td class="text-center font-weight-bold"><?= esc($row['total_hadir']); ?></td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($start_date && $end_date): ?>
        <div class="alert alert-info mt-4">
            No attendance data found for the selected filter.
        </div>
    <?php endif; ?>

</div>