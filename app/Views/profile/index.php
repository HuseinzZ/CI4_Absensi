<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= esc($title); ?></h1>

    <div class="row">
        <div class="col-sm-10 offset-sm-1">
            <div class="card shadow mb-4">
                <div class="card-body d-flex flex-column justify-content-center">

                    <div class="row align-items-center">
                        <!-- Profile Image -->
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <img src="<?= base_url('assets/img/profile/' . esc($account['image'])); ?>"
                                alt="<?= esc($account['name']); ?>'s Profile Picture"
                                class="img-fluid rounded-circle border border-primary p-2 shadow-sm"
                                style="width: 180px; height: 180px; object-fit: cover; object-position: top;">
                            <h4 class="mt-3 text-gray-800"><?= esc($account['name']); ?></h4>
                            <p class="text-muted mb-0"><?= esc($account['position_name']); ?></p>
                        </div>

                        <!-- Profile Data -->
                        <div class="col-md-8">
                            <h4 class="mb-4 text-gray-800">Personal Data</h4>
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <th scope="row" class="w-25">Employee ID</th>
                                        <td><?= esc($account['id']); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Gender</th>
                                        <td><?= ($account['gender'] === 'M') ? 'Male' : 'Female'; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Position</th>
                                        <td><?= esc($account['position_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Birthday</th>
                                        <td><?= esc($account['birth_date']); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Joined On</th>
                                        <td><?= esc($account['hire_date']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- /.row -->

                </div>
            </div>
        </div>
    </div>

</div>