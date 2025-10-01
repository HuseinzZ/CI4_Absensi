<div class="container-fluid">
    <?php $session = \Config\Services::session(); ?>

    <?php if ($session->getFlashdata('message')) : ?>
        <?= $session->getFlashdata('message'); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Users</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($data as $dt) : ?>
                            <tr>
                                <td class="align-middle"><?= $i++; ?></td>
                                <td class="align-middle"><?= esc($dt['e_id']); ?></td>
                                <td class="align-middle"><?= esc($dt['e_name']); ?></td>
                                <td class="align-middle"><?= esc($dt['d_id']); ?></td>

                                <?php if ($dt['u_username']) : ?>
                                    <td class="align-middle text-center">
                                        <?= esc($dt['u_username']); ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="<?= site_url('master/e_users/') . esc($dt['u_username']); ?>"
                                            class="btn btn-warning btn-icon-split btn-sm" title="Edit">
                                            <span class="icon text-white">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                        </a>
                                        <a href="<?= site_url('master/d_users/') . esc($dt['u_username']); ?>"
                                            class="btn btn-danger btn-icon-split btn-sm ml-2"
                                            onclick="return confirm('Deleted user will be lost forever. Still want to delete?')"
                                            title="Delete">
                                            <span class="icon text-white">
                                                <i class="fas fa-trash-alt"></i>
                                            </span>
                                        </a>
                                    </td>
                                <?php else : ?>
                                    <td class="align-middle text-center">
                                        <a href="<?= site_url('master/a_users/') . esc($dt['e_id']) . '/' . esc($dt['d_id']); ?>"
                                            class="btn btn-primary btn-sm">
                                            Create Account
                                        </a>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-warning btn-icon-split btn-sm" disabled>
                                            <span class="icon text-white">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                        </button>
                                        <button class="btn btn-danger btn-icon-split btn-sm ml-2" disabled>
                                            <span class="icon text-white">
                                                <i class="fas fa-trash-alt"></i>
                                            </span>
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>