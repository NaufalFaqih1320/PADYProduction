<div class="crew-tabs">

    <a href="dashboard.php" class="crew-tab <?= ($currentTab ?? '') === 'dashboard' ? 'active' : ''; ?>">
        Dashboard
    </a>

    <a href="booking.php" class="crew-tab <?= ($currentTab ?? '') === 'booking' ? 'active' : ''; ?>">
        Daftar Booking
    </a>

    <a href="jadwal.php" class="crew-tab <?= ($currentTab ?? '') === 'jadwal' ? 'active' : ''; ?>">
        Jadwal
    </a>

    <a href="inventaris.php" class="crew-tab <?= ($currentTab ?? '') === 'inventaris' ? 'active' : ''; ?>">
        Inventaris
    </a>

    <a href="chat.php" class="crew-tab <?= ($currentTab ?? '') === 'chat' ? 'active' : ''; ?>">
        Chat
    </a>

    <a href="reminder.php" class="crew-tab <?= ($currentTab ?? '') === 'reminder' ? 'active' : ''; ?>">
        Reminder
    </a>

    <a href="laporan.php" class="crew-tab <?= ($currentTab ?? '') === 'laporan' ? 'active' : ''; ?>">
        Laporan
    </a>

</div>