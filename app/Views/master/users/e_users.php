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
                    <h6 class="m-0 font-weight-bold text-primary">Edit Account</h6>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('master/e_users/' . $user['username']); ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="form-group row">
                            <label for="u_username" class="col-sm-4 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text"
                                    class="form-control"
                                    id="u_username"
                                    name="u_username"
                                    value="<?= esc($user['username']); ?>"
                                    readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="u_password" class="col-sm-4 col-form-label">New Password</label>
                            <div class="col-sm-8">
                                <input type="password"
                                    class="form-control <?= $validation->hasError('u_password') ? 'is-invalid' : ''; ?>"
                                    id="u_password" name="u_password">

                                <?php if ($validation->getError('u_password')) : ?>
                                    <small class="text-danger pl-3"><?= $validation->getError('u_password'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="u_password2" class="col-sm-4 col-form-label">Repeat New Password</label>
                            <div class="col-sm-8">
                                <input type="password"
                                    class="form-control <?= $validation->hasError('u_password2') ? 'is-invalid' : ''; ?>"
                                    id="u_password2" name="u_password2">

                                <?php if ($validation->getError('u_password2')) : ?>
                                    <small class="text-danger pl-3"><?= $validation->getError('u_password2'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('master/users'); ?>" class="btn btn-secondary btn-icon-split">
                                <span class="icon text-white">
                                    <i class="fas fa-arrow-left"></i>
                                </span>
                                <span class="text">Back</span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span class="text">Update Password</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>