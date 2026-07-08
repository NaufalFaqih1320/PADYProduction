<?php

require_once "../config/database.php";
require_once "../config/auth.php";

adminOnly();

$currentTab = 'dashboard';

/* ======================================
   STAT: TOTAL PENGGUNA
====================================== */

$totalUser = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM users")
)['total'];

/* ======================================
   STAT: TOTAL BOOKING
====================================== */

$totalBooking = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM booking")
)['total'];

/* ======================================
   STAT: BOOKING PENDING
====================================== */

$bookingPending = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM booking WHERE status = 'Pending'")
)['total'];

/* ======================================
   STAT: STOK MENIPIS (<= 5 UNIT)
====================================== */

$stokMenipis = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM inventaris WHERE stok <= 5")
)['total'];

/* ======================================
   BOOKING TERBARU (5 TERATAS)
====================================== */

$queryBookingTerbaru = mysqli_query($conn, "
    SELECT id_booking, nama_acara, nama_client, lokasi, tanggal_acara, waktu_acara, status
    FROM booking
    ORDER BY created_at DESC
    LIMIT 5
");

/* ======================================
   PENGGUNA TERBARU (5 TERATAS)
====================================== */

$queryUserTerbaru = mysqli_query($conn, "
    SELECT id_user, nama, email, role, status
    FROM users
    ORDER BY created_at DESC
    LIMIT 5
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/crew.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "../includes/layout/topbar.php"; ?>

<?php include "../includes/components/admin_tabs.php"; ?>

<div class="crew-content">

    <div class="crew-page-header">
        <h2>Dashboard</h2>
    </div>

    <!-- STAT CARDS -->
    <div class="crew-statistik">

        <div class="crew-stat-card">
            <h2><?= $totalUser; ?></h2>
            <p>Total Pengguna</p>
        </div>

        <div class="crew-stat-card">
            <h2><?= $totalBooking; ?></h2>
            <p>Total Booking</p>
        </div>

        <div class="crew-stat-card">
            <h2><?= $bookingPending; ?></h2>
            <p>Booking Pending</p>
        </div>

        <div class="crew-stat-card warning">
            <h2><?= $stokMenipis; ?></h2>
            <p>Stok Menipis</p>
        </div>

    </div>

    <!-- DUA PANEL: BOOKING TERBARU & PENGGUNA TERBARU -->
    <div class="crew-dashboard-grid">

        <div class="crew-panel">

            <div class="crew-panel-header">
                <h3>Booking Terbaru</h3>
                <a href="booking.php" class="link-lihat-semua">Lihat Semua</a>
            </div>

            <?php if (mysqli_num_rows($queryBookingTerbaru) > 0): ?>

                <ul class="jadwal-list">

                    <?php while ($b = mysqli_fetch_assoc($queryBookingTerbaru)): ?>

                        <li class="jadwal-item">

                            <div class="jadwal-tanggal">
                                <span class="jadwal-tgl-angka">
                                    <?= $b['tanggal_acara'] ? date("d", strtotime($b['tanggal_acara'])) : '-'; ?>
                                </span>
                                <span class="jadwal-tgl-bulan">
                                    <?= $b['tanggal_acara'] ? date("M", strtotime($b['tanggal_acara'])) : ''; ?>
                                </span>
                            </div>

                            <div class="jadwal-info">
                                <h4><?= htmlspecialchars($b['nama_acara'] ?? '-'); ?></h4>
                                <p>
                                    <?= htmlspecialchars($b['nama_client']); ?> &middot;
                                    <?= htmlspecialchars($b['lokasi'] ?? '-'); ?>
                                </p>
                            </div>

                            <span class="status <?= strtolower($b['status']); ?>">
                                <?= htmlspecialchars($b['status']); ?>
                            </span>

                        </li>

                    <?php endwhile; ?>

                </ul>

            <?php else: ?>

                <p class="crew-empty">Belum ada data booking.</p>

            <?php endif; ?>

        </div>

        <div class="crew-panel">

            <div class="crew-panel-header">
                <h3>Pengguna Terbaru</h3>
                <a href="users.php" class="link-lihat-semua">Lihat Semua</a>
            </div>

            <?php if (mysqli_num_rows($queryUserTerbaru) > 0): ?>

                <ul class="stok-list">

                    <?php while ($u = mysqli_fetch_assoc($queryUserTerbaru)): ?>

                        <li class="stok-item">

                            <div class="stok-info">
                                <h4><?= htmlspecialchars($u['nama']); ?></h4>
                                <p><?= htmlspecialchars($u['email']); ?></p>
                            </div>

                            <div class="stok-angka">
                                <span class="role-badge <?= strtolower($u['role']); ?>">
                                    <?= htmlspecialchars($u['role']); ?>
                                </span>
                            </div>

                        </li>

                    <?php endwhile; ?>

                </ul>

            <?php else: ?>

                <p class="crew-empty">Belum ada data pengguna.</p>

            <?php endif; ?>

        </div>

    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="../assets/js/admin.js"></script>

</body>
</html>
