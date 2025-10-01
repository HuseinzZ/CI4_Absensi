<div class="container-fluid">
    <?php $session = \Config\Services::session(); ?>

    <?php if ($session->getFlashdata('message')) : ?>
        <?= $session->getFlashdata('message'); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Position</h6>

            <a href="<?= site_url('master/a_position'); ?>" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <span class="text">Add new position</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Position Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($position as $ptt): ?>
                            <tr>
                                <td class="align-middle"><?= $i++; ?></td>
                                <td class="align-middle"><?= esc($ptt['id']); ?></td>
                                <td class="align-middle"><?= esc($ptt['name']); ?></td>
                                <td class="align-middle text-center">
                                    <a href="<?= site_url('master/e_position/' . $ptt['id']); ?>"
                                        class="btn btn-warning btn-icon-split btn-sm">
                                        <span class="icon text-white">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                    </a>

                                    <a href="<?= site_url('master/d_position/' . $ptt['id']); ?>"
                                        onclick="return confirm('Deleted Position will be lost forever. Still want to delete?');"
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