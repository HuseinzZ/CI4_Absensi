<div class="container-fluid">
    <?php $session = \Config\Services::session(); ?>
    <?php $validation = \Config\Services::validation(); ?>

    <?php if ($session->getFlashdata('message')) : ?>
        <?= $session->getFlashdata('message'); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Employee</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('master/e_employee/' . $employee['id']); ?>" method="post" enctype="multipart/form-data">

                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="emp_id">ID</label>
                            <input type="text" class="form-control" id="emp_id" name="emp_id"
                                value="<?= $employee['id']; ?>" readonly>
                            <input type="hidden" name="id" value="<?= $employee['id']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="emp_name">Name</label>
                            <input type="text"
                                class="form-control <?= $validation->hasError('emp_name') ? 'is-invalid' : ''; ?>"
                                id="emp_name" name="emp_name"
                                value="<?= old('emp_name', $employee['name']); ?>">

                            <?php if ($validation->getError('emp_name')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_name'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="emp_email">Email</label>
                            <input type="email"
                                class="form-control <?= $validation->hasError('emp_email') ? 'is-invalid' : ''; ?>"
                                id="emp_email" name="emp_email"
                                value="<?= old('emp_email', $employee['email']); ?>">

                            <?php if ($validation->getError('emp_email')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_email'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Gender</label>
                            <?php $selectedGender = old('emp_gender', $employee['gender']); ?>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="emp_gender" id="genderMale"
                                    value="M" <?= ($selectedGender == 'M') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="genderMale">Male</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="emp_gender" id="genderFemale"
                                    value="F" <?= ($selectedGender == 'F') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="genderFemale">Female</label>
                            </div>

                            <?php if ($validation->getError('emp_gender')) : ?>
                                <small class="text-danger d-block"><?= $validation->getError('emp_gender'); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="emp_birth_date">Date of Birth</label>
                            <input type="date"
                                class="form-control <?= $validation->hasError('emp_birth_date') ? 'is-invalid' : ''; ?>"
                                id="emp_birth_date" name="emp_birth_date"
                                value="<?= old('emp_birth_date', $employee['birth_date']); ?>">

                            <?php if ($validation->getError('emp_birth_date')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_birth_date'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="emp_hire_date">Hire Date</label>
                            <input type="date"
                                class="form-control <?= $validation->hasError('emp_hire_date') ? 'is-invalid' : ''; ?>"
                                id="emp_hire_date" name="emp_hire_date"
                                value="<?= old('emp_hire_date', $employee['hire_date']); ?>">

                            <?php if ($validation->getError('emp_hire_date')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_hire_date'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="emp_position_id">Position</label>
                            <select name="emp_position_id" id="emp_position_id"
                                class="form-control <?= $validation->hasError('emp_position_id') ? 'is-invalid' : ''; ?>">
                                <option value="">Select Position</option>
                                <?php $selectedPosition = old('emp_position_id', $employee['position_id']); ?>
                                <?php foreach ($positions as $p): ?>
                                    <option value="<?= $p['id']; ?>" <?= ($selectedPosition == $p['id']) ? 'selected' : ''; ?>>
                                        <?= $p['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <?php if ($validation->getError('emp_position_id')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_position_id'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Current Image</label><br>
                            <img src="<?= base_url('assets/img/profile/') . $employee['image']; ?>"
                                class="img-thumbnail mb-2"
                                style="height: 250px; width: 100%; object-fit: cover; object-position: top;">
                            <div class="custom-file">
                                <input type="file"
                                    class="custom-file-input <?= $validation->hasError('emp_image') ? 'is-invalid' : ''; ?>"
                                    id="emp_image" name="emp_image">
                                <label class="custom-file-label" for="emp_image">Choose new file</label>
                            </div>

                            <?php if ($validation->getError('emp_image')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_image'); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('master/'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>