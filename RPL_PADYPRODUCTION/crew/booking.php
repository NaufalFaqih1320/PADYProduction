<?php

require_once "../config/database.php";
require_once "../config/auth.php";

crewOnly();

$currentTab = 'booking';

/* ======================================
   QUERY BOOKING + DAFTAR ALAT
====================================== */

$queryBooking = mysqli_query($conn, "
    SELECT *
    FROM booking
    ORDER BY
        CASE WHEN status = 'Pending' THEN 0 ELSE 1 END ASC,
        tanggal_acara ASC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booking - Crew</title>
    <link rel="stylesheet" href="../assets/css/crew.css">
</head>
<body>

<?php include "../includes/layout/topbar.php"; ?>

<?php include "../includes/components/crew_tabs.php"; ?>

<div class="crew-content">

    <div class="crew-page-header">
        <h2>Daftar Booking</h2>
    </div>

    <!-- TOOLBAR: SEARCH + FILTER STATUS -->
    <div class="crew-toolbar">

        <div class="crew-search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input
                type="text"
                id="searchBookingCrew"
                placeholder="Cari nama acara, client, atau lokasi...">
        </div>

        <div class="crew-filter-pills" id="statusPills">
            <button type="button" class="pill active" data-status="semua">Semua</button>
            <button type="button" class="pill" data-status="pending">Pending</button>
            <button type="button" class="pill" data-status="selesai">Selesai</button>
        </div>

    </div>

    <!-- LIST BOOKING -->
    <div class="booking-crew-list" id="bookingCrewList">

        <?php if (mysqli_num_rows($queryBooking) > 0): ?>

            <?php while ($booking = mysqli_fetch_assoc($queryBooking)): ?>

                <?php
                    $queryDetail = mysqli_query($conn, "
                        SELECT i.nama_barang, bd.jumlah
                        FROM booking_detail bd
                        JOIN inventaris i ON bd.id_inventaris = i.id_inventaris
                        WHERE bd.id_booking = {$booking['id_booking']}
                    ");
                ?>

                <div class="booking-crew-card"
                     data-status="<?= strtolower($booking['status']); ?>"
                     data-search="<?= strtolower(
                        $booking['nama_acara'] . ' ' .
                        $booking['nama_client'] . ' ' .
                        $booking['lokasi']
                     ); ?>">

                    <div class="booking-crew-header">

                        <div>
                            <h3><?= htmlspecialchars($booking['nama_acara']); ?></h3>
                            <small><?= date("d F Y", strtotime($booking['tanggal_acara'])); ?> &middot; <?= htmlspecialchars($booking['waktu_acara']); ?></small>
                        </div>

                        <span class="status <?= strtolower($booking['status']); ?>">
                            <?= htmlspecialchars($booking['status']); ?>
                        </span>

                    </div>

                    <div class="booking-crew-body">

                        <div class="booking-crew-item">
                            <label>Client</label>
                            <p><?= htmlspecialchars($booking['nama_client']); ?></p>
                        </div>

                        <div class="booking-crew-item">
                            <label>No. HP</label>
                            <p><?= htmlspecialchars($booking['no_hp']); ?></p>
                        </div>

                        <div class="booking-crew-item">
                            <label>Lokasi</label>
                            <p><?= htmlspecialchars($booking['lokasi']); ?></p>
                        </div>

                        <div class="booking-crew-item">
                            <label>Catatan</label>
                            <p><?= !empty($booking['catatan']) ? htmlspecialchars($booking['catatan']) : "-"; ?></p>
                        </div>

                        <div class="booking-crew-item full">
                            <label>Alat yang Harus Disiapkan</label>
                            <ul class="booking-crew-tools">
                                <?php while ($detail = mysqli_fetch_assoc($queryDetail)): ?>
                                    <li>
                                        <?= htmlspecialchars($detail['nama_barang']); ?>
                                        <span><?= $detail['jumlah']; ?> unit</span>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="booking-crew-card">
                <h3>Belum ada data booking.</h3>
            </div>

        <?php endif; ?>

    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="../assets/js/crew.js"></script>

</body>
</html>