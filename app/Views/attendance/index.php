<div class="container mt-2">
    <?php $session = \Config\Services::session(); ?>

    <?php if ($session->getFlashdata('message')) : ?>
        <?= $session->getFlashdata('message'); ?>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <div class="row text-center mb-4">
                <div class="col">
                    <?php if (!$attendance) : ?>
                        <h4 class="fw-bold">Welcome, <?= esc($account['name']); ?>!</h4>
                        <p class="text-muted mb-0">Please do your attendance today.</p>
                    <?php elseif ($attendance && !$attendance['check_out']) : ?>
                        <h4 class="fw-bold">Thank you, <?= esc($account['name']); ?>!</h4>
                        <p class="text-muted mb-0">You've done a great job today.</p>
                        <p class="text-muted mb-0">Don't forget to check out.</p>
                    <?php else : ?>
                        <h4 class="fw-bold">Congrats, <?= esc($account['name']); ?>!</h4>
                        <p class="text-muted mb-0">Your attendance for today is complete.</p>
                        <p class="text-muted mb-0">See you tomorrow.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mb-4">
                <h1 class="display-4 fw-bold" id="current-time"></h1>
                <p class="lead" id="current-date"></p>
            </div>

            <?php if (!$attendance || ($attendance && !$attendance['check_out'])) : ?>
                <div class="card bg-light mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title">Your Current Location</h5>
                        <div class="spinner-border text-primary" role="status" id="loading-spinner">
                            <span class="visually-hidden">Searching for location...</span>
                        </div>
                        <p class="lead mt-3" id="location-info">Searching for location...</p>
                        <a href="#" id="view-on-map-link"
                            class="btn btn-sm btn-outline-primary d-none"
                            target="_blank">
                            <i class="fas fa-map-marker-alt me-1"></i> View on Google Maps
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!$attendance) : ?>
                <div class="text-center">
                    <form method="post" action="<?= site_url('attendance/do_attendance'); ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <button type="submit" class="btn btn-success btn-lg" id="absen-button" disabled>
                            <i class="fas fa-sign-in-alt me-2"></i> Check-in
                        </button>
                    </form>
                </div>

            <?php elseif ($attendance && !$attendance['check_out']) : ?>
                <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
                    <div>
                        <i class="fas fa-clock me-2"></i>
                        Check-in Time: <strong><?= esc($attendance['check_in']); ?></strong>
                    </div>
                    <span class="badge text-white bg-<?= ($attendance['status_in'] === 'Late') ? 'danger' : 'success'; ?>">
                        <?= esc($attendance['status_in']); ?>
                    </span>
                </div>

                <div class="text-center">
                    <form method="post" action="<?= site_url('attendance/do_attendance'); ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <button type="submit" class="btn btn-danger btn-lg" id="absen-pulang-button" disabled>
                            <i class="fas fa-sign-out-alt me-2"></i> Check-out
                        </button>
                    </form>
                </div>

            <?php else : ?>
                <div class="alert alert-success d-flex flex-column align-items-center py-4" role="alert">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h4 class="alert-heading">Attendance Complete!</h4>
                    <p class="mb-1">
                        Check-in Time: <strong><?= esc($attendance['check_in']); ?></strong> (<?= esc($attendance['status_in']); ?>)
                    </p>
                    <p class="mb-0">
                        Check-out Time: <strong><?= esc($attendance['check_out']); ?></strong> (<?= esc($attendance['status_out']); ?>)
                    </p>
                </div>

                <div class="text-center mt-3">
                    <a href="<?= site_url('history'); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-history me-1"></i> View History
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    // Real-time clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const dateString = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('current-time').textContent = timeString;
        document.getElementById('current-date').textContent = dateString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Geolocation
    function getLocation() {
        const absenButton = document.getElementById('absen-button');
        const absenPulangButton = document.getElementById('absen-pulang-button');
        const locationInfo = document.getElementById('location-info');
        const spinner = document.getElementById('loading-spinner');
        const viewOnMapLink = document.getElementById('view-on-map-link');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    const latitudeInput = document.getElementById('latitude');
                    const longitudeInput = document.getElementById('longitude');

                    if (latitudeInput) latitudeInput.value = lat;
                    if (longitudeInput) longitudeInput.value = lng;

                    if (locationInfo) {
                        locationInfo.innerHTML = `Location successfully retrieved:<br>Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
                    }
                    if (spinner) spinner.classList.add('d-none');

                    if (viewOnMapLink) {
                        // KOREKSI URL Google Maps (menggunakan template literal JS)
                        viewOnMapLink.href = `http://maps.google.com/maps?q=${lat},${lng}`;
                        viewOnMapLink.classList.remove('d-none');
                    }

                    if (absenButton) absenButton.disabled = false;
                    if (absenPulangButton) absenPulangButton.disabled = false;
                },
                function(error) {
                    if (locationInfo) locationInfo.textContent = "Failed to get location. Please try again.";
                    if (spinner) spinner.classList.add('d-none');
                    if (absenButton) absenButton.disabled = false;
                    if (absenPulangButton) absenPulangButton.disabled = false;
                    console.error("Error getting geolocation: ", error);
                }
            );
        } else {
            if (locationInfo) locationInfo.textContent = "Geolocation is not supported by this browser.";
            if (spinner) spinner.classList.add('d-none');
            console.error("Geolocation is not supported by this browser.");
        }
    }

    if (document.getElementById('location-info')) {
        getLocation();
    }
</script>