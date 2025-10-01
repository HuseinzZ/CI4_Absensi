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
                    <h6 class="m-0 font-weight-bold text-primary">Edit Position</h6>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('master/e_position/' . $old_position['id']); ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="form-group row">
                            <label for="p_id" class="col-sm-4 col-form-label">Position ID</label>
                            <div class="col-sm-8">
                                <input type="text"
                                    class="form-control"
                                    id="p_id"
                                    name="p_id"
                                    value="<?= esc($old_position['id']); ?>"
                                    readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="p_name" class="col-sm-4 col-form-label">Position Name</label>
                            <div class="col-sm-8">
                                <input type="text"
                                    class="form-control <?= $validation->hasError('p_name') ? 'is-invalid' : ''; ?>"
                                    id="p_name"
                                    name="p_name"
                                    value="<?= old('p_name', $old_position['name']); ?>">

                                <?php if ($validation->getError('p_name')) : ?>
                                    <small class="text-danger pl-3"><?= $validation->getError('p_name'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('master/position'); ?>" class="btn btn-secondary btn-icon-split">
                                <span class="icon text-white">
                                    <i class="fas fa-arrow-left"></i>
                                </span>
                                <span class="text">Back</span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span class="text">Update</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>