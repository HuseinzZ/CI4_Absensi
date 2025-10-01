<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark">Attendance History</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center">
                        <tr>
                            <th>Date</th>
                            <th>Check-in</th>
                            <th>Status In</th>
                            <th>Check-out</th>
                            <th>Status Out</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($history) : ?>
                            <?php foreach ($history as $row) : ?>
                                <tr>
                                    <td class="text-center">
                                        <?= date('d-m-Y', strtotime(esc($row['attendance_date']))); ?>
                                    </td>
                                    <td class="text-center">
                                        <?= esc($row['check_in']) ?: '-'; ?>
                                    </td>
                                    <td class="text-center text-white">
                                        <?php if ($row['status_in']) : ?>
                                            <span class="badge bg-<?= (esc($row['status_in']) === 'Late') ? 'danger' : 'success'; ?>">
                                                <?= esc($row['status_in']); ?>
                                            </span>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= esc($row['check_out']) ?: '-'; ?>
                                    </td>
                                    <td class="text-center text-white">
                                        <?php if ($row['status_out']) : ?>
                                            <span class="badge bg-<?= (esc($row['status_out']) === 'Left Early') ? 'warning' : 'info'; ?>">
                                                <?= esc($row['status_out']); ?>
                                            </span>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['latitude'] && $row['longitude']) : ?>
                                            <a href="https://maps.google.com/?q=<?= esc($row['latitude']); ?>,<?= esc($row['longitude']); ?>"
                                                target="_blank" class="text-primary">
                                                View Location
                                            </a>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="lead text-muted">No attendance history available.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>