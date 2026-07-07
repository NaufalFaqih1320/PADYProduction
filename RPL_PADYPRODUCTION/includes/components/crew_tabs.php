<div class="crew-tabs">

    <a href="dashboard.php" class="crew-tab <?= ($currentTab ?? '') === 'dashboard' ? 'active' : ''; ?>">
        Dashboard
    </a>

    <a href="inventaris.php" class="crew-tab <?= ($currentTab ?? '') === 'inventaris' ? 'active' : ''; ?>">
        Kelola Inventaris
    </a>

    <a href="booking.php" class="crew-tab <?= ($currentTab ?? '') === 'booking' ? 'active' : ''; ?>">
        Daftar Booking
    </a>

    <a href="jadwal.php" class="crew-tab <?= ($currentTab ?? '') === 'jadwal' ? 'active' : ''; ?>">
        Jadwal
    </a>

</div>