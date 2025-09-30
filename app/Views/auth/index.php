<div class="container vh-100 d-flex flex-column justify-content-start mt-4 justify-content-lg-center mt-lg-0 align-items-center">

    <div class="login-card p-5 text-center shadow rounded-3" style="max-width: 450px; width: 100%;">
        <a href="https://share.google/UMIjpeyn1IW6l9pPA">
            <img src="<?= base_url('assets/img/1.png') ?>" alt="Logo Ayyuwa" width="100" class="mb-3">
        </a>
        <h5 class="fw-bold mb-4">LOGIN ABSENSI</h5>

        <?php $session = \Config\Services::session(); ?>
        <?php if ($session->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc($session->getFlashdata('error')); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($session->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc($session->getFlashdata('success')); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($validation) && $validation->getErrors()) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Login failed. Please check your inputs.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('auth'); ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3 text-start">
                <label for="username" class="form-label text-secondary fw-bold small">
                    Username <span class="text-danger">*</span>
                </label>
                <input type="text"
                    class="form-control <?= isset($validation) && $validation->hasError('username') ? 'is-invalid' : '' ?>"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    required
                    value="<?= old('username'); ?>">
                <?php if (isset($validation) && $validation->hasError('username')) : ?>
                    <div class="invalid-feedback">
                        <?= esc($validation->getError('username')); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3 text-start">
                <label for="password" class="form-label text-secondary fw-bold small">
                    Password <span class="text-danger">*</span>
                </label>
                <input type="password"
                    class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required>
                <?php if (isset($validation) && $validation->hasError('password')) : ?>
                    <div class="invalid-feedback">
                        <?= esc($validation->getError('password')); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Log In</button>
            </div>
        </form>
    </div>

    <div class="mt-3">
        <p class="text-secondary text-center mb-0 small">
            &copy; TOKO KURMA AYYUWA <?= date('Y'); ?>
        </p>
    </div>
</div>