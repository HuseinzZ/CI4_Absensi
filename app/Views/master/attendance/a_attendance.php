<div class="container-fluid">
    <?php $session = \Config\Services::session(); ?>
    <?php $validation = \Config\Services::validation(); ?>

    <div class="row">
        <div class="col-lg-12">

            <?php if ($session->getFlashdata('message')) : ?>
                <?= $session->getFlashdata('message'); ?>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Add Attendance</h6>
                </div>

                <div class="card-body">
                    <form action="<?= site_url('master/a_attendance'); ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="form-group row">
                            <label for="employee_id" class="col-sm-4 col-form-label">Employee</label>
                            <div class="col-sm-8">
                                <select class="form-control <?= $validation->hasError('employee_id') ? 'is-invalid' : ''; ?>" id="employee_id" name="employee_id">
                                    <option value="">Select Employee</option>
                                    <?php foreach ($employees as $employee): ?>
                                        <option value="<?= $employee['id']; ?>" <?= old('employee_id') == $employee['id'] ? 'selected' : ''; ?>>
                                            <?= esc($employee['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($validation->getError('employee_id')) : ?>
                                    <small class="text-danger pl-3"><?= $validation->getError('employee_id'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="attendance_date" class="col-sm-4 col-form-label">Attendance Date</label>
                            <div class="col-sm-8">
                                <input type="date"
                                    class="form-control <?= $validation->hasError('attendance_date') ? 'is-invalid' : ''; ?>"
                                    id="attendance_date"
                                    name="attendance_date" value="<?= old('attendance_date'); ?>">

                                <?php if ($validation->getError('attendance_date')) : ?>
                                    <small class="text-danger pl-3"><?= $validation->getError('attendance_date'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="check_in" class="col-sm-4 col-form-label">Check-in Time</label>
                            <div class="col-sm-8">
                                <input type="time"
                                    class="form-control <?= $validation->hasError('check_in') ? 'is-invalid' : ''; ?>"
                                    id="check_in"
                                    name="check_in" value="<?= old('check_in'); ?>">

                                <?php if ($validation->getError('check_in')) : ?>
                                    <small class="text-danger pl-3"><?= $validation->getError('check_in'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status_in" class="col-sm-4 col-form-label">Status In</label>
                            <div class="col-sm-8">
                                <select class="form-control <?= $validation->hasError('status_in') ? 'is-invalid' : ''; ?>" id="status_in" name="status_in">
                                    <option value="">Select Status</option>
                                    <option value="Present" <?= old('status_in') == 'Present' ? 'selected' : ''; ?>>Present</option>
                                    <option value="Late" <?= old('status_in') == 'Late' ? 'selected' : ''; ?>>Late</option>
                                </select>
                                <?php if ($validation->getError('status_in')) : ?>
                                    <small class="text-danger pl-3"><?= $validation->getError('status_in'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="check_out" class="col-sm-4 col-form-label">Check-out Time</label>
                            <div class="col-sm-8">
                                <input type="time"
                                    class="form-control <?= $validation->hasError('check_out') ? 'is-invalid' : ''; ?>"
                                    id="check_out"
                                    name="check_out" value="<?= old('check_out'); ?>">

                                <?php if ($validation->getError('check_out')) : ?>
                                    <small class="text-danger pl-3"><?= $validation->getError('check_out'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status_out" class="col-sm-4 col-form-label">Status Out</label>
                            <div class="col-sm-8">
                                <select class="form-control <?= $validation->hasError('status_out') ? 'is-invalid' : ''; ?>" id="status_out" name="status_out">
                                    <option value="">Select Status</option>
                                    <option value="On Time" <?= old('status_out') == 'On Time' ? 'selected' : ''; ?>>On Time</option>
                                    <option value="Left Early" <?= old('status_out') == 'Left Early' ? 'selected' : ''; ?>>Left Early</option>
                                </select>
                                <?php if ($validation->getError('status_out')) : ?>
                                    <small class="text-danger pl-3"><?= $validation->getError('status_out'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <input type="hidden" name="latitude" value="<?= old('latitude'); ?>">
                        <input type="hidden" name="longitude" value="<?= old('longitude'); ?>">

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('master/attendance'); ?>" class="btn btn-secondary btn-icon-split">
                                <span class="icon text-white"><i class="fas fa-arrow-left"></i></span>
                                <span class="text">Back</span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white"><i class="fas fa-save"></i></span>
                                <span class="text">Add</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>