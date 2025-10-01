<div class="container-fluid">
    <?php $session = \Config\Services::session(); ?>
    <?php $validation = \Config\Services::validation(); ?>

    <?php if ($session->getFlashdata('message')) : ?>
        <?= $session->getFlashdata('message'); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Add New Employee</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('master/a_employee'); ?>" method="post" enctype="multipart/form-data">

                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="emp_name">Name</label>
                            <input type="text"
                                class="form-control <?= $validation->hasError('emp_name') ? 'is-invalid' : ''; ?>"
                                id="emp_name" name="emp_name"
                                value="<?= old('emp_name'); ?>">

                            <?php if ($validation->getError('emp_name')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_name'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="emp_email">Email</label>
                            <input type="email"
                                class="form-control <?= $validation->hasError('emp_email') ? 'is-invalid' : ''; ?>"
                                id="emp_email" name="emp_email"
                                value="<?= old('emp_email'); ?>">

                            <?php if ($validation->getError('emp_email')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_email'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Gender</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="emp_gender" id="genderMale"
                                    value="M" <?= old('emp_gender') == 'M' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="genderMale">Male</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="emp_gender" id="genderFemale"
                                    value="F" <?= old('emp_gender') == 'F' ? 'checked' : ''; ?>>
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
                                value="<?= old('emp_birth_date'); ?>">

                            <?php if ($validation->getError('emp_birth_date')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_birth_date'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="emp_hire_date">Hire Date</label>
                            <input type="date"
                                class="form-control <?= $validation->hasError('emp_hire_date') ? 'is-invalid' : ''; ?>"
                                id="emp_hire_date" name="emp_hire_date"
                                value="<?= old('emp_hire_date'); ?>">

                            <?php if ($validation->getError('emp_hire_date')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_hire_date'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="emp_position_id">Position</label>
                            <select name="emp_position_id" id="emp_position_id"
                                class="form-control <?= $validation->hasError('emp_position_id') ? 'is-invalid' : ''; ?>">
                                <option value="">Select Position</option>
                                <?php foreach ($positions as $p): ?>
                                    <option value="<?= $p['id']; ?>" <?= old('emp_position_id') == $p['id'] ? 'selected' : ''; ?>>
                                        <?= $p['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <?php if ($validation->getError('emp_position_id')) : ?>
                                <small class="text-danger"><?= $validation->getError('emp_position_id'); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="emp_image">Image</label>
                            <div class="custom-file">
                                <input type="file"
                                    class="custom-file-input <?= $validation->hasError('emp_image') ? 'is-invalid' : ''; ?>"
                                    id="emp_image" name="emp_image">
                                <label class="custom-file-label" for="emp_image">Choose file</label>
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
                        <i class="fas fa-save"></i> Add
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>