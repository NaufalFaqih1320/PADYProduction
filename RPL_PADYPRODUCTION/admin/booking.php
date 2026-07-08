<?php

require_once "../config/database.php";
require_once "../config/auth.php";

adminOnly();

$currentTab = 'booking';

/* ======================================
   QUERY BOOKING
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
    <title>Daftar Booking - Admin</title>
    <link rel="stylesheet" href="../assets/css/crew.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "../includes/layout/topbar.php"; ?>

<?php include "../includes/components/admin_tabs.php"; ?>

<div class="crew-content">

    <div class="crew-page-header">

        <h2>Daftar Booking</h2>

        <a href="tambah_booking.php" class="btn-add-inventaris" style="text-decoration:none;">
            + Tambah Booking
        </a>

    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="admin-alert success"><?= htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="admin-alert error"><?= htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <!-- TOOLBAR: SEARCH + FILTER STATUS -->
    <div class="crew-toolbar">

        <div class="crew-search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input
                type="text"
                id="searchBookingAdmin"
                placeholder="Cari nama acara, client, atau lokasi...">
        </div>

        <div class="crew-filter-pills" id="statusPillsAdmin">
            <button type="button" class="pill active" data-status="semua">Semua</button>
            <button type="button" class="pill" data-status="pending">Pending</button>
            <button type="button" class="pill" data-status="dikonfirmasi">Dikonfirmasi</button>
            <button type="button" class="pill" data-status="berlangsung">Berlangsung</button>
            <button type="button" class="pill" data-status="selesai">Selesai</button>
            <button type="button" class="pill" data-status="dibatalkan">Dibatalkan</button>
        </div>

    </div>

    <!-- LIST BOOKING -->
    <div class="booking-crew-list" id="bookingAdminList">

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
                     data-search="<?= strtolower(htmlspecialchars(
                        $booking['nama_acara'] . ' ' .
                        $booking['nama_client'] . ' ' .
                        $booking['lokasi']
                     )); ?>">

                    <div class="booking-crew-header">

                        <div>
                            <h3><?= htmlspecialchars($booking['nama_acara'] ?? '-'); ?></h3>
                            <small>
                                <?= $booking['tanggal_acara'] ? date("d F Y", strtotime($booking['tanggal_acara'])) : '-'; ?>
                                &middot; <?= htmlspecialchars($booking['waktu_acara'] ?? '-'); ?>
                            </small>
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
                            <label>Email</label>
                            <p><?= htmlspecialchars($booking['email_client']); ?></p>
                        </div>

                        <div class="booking-crew-item">
                            <label>No. HP</label>
                            <p><?= htmlspecialchars($booking['no_hp'] ?? '-'); ?></p>
                        </div>

                        <div class="booking-crew-item">
                            <label>Lokasi</label>
                            <p><?= htmlspecialchars($booking['lokasi'] ?? '-'); ?></p>
                        </div>

                        <div class="booking-crew-item">
                            <label>Tanggal Pemasangan</label>
                            <p><?= $booking['tanggal_pemasangan'] ? date("d F Y", strtotime($booking['tanggal_pemasangan'])) : '-'; ?></p>
                        </div>

                        <div class="booking-crew-item">
                            <label>Catatan</label>
                            <p><?= !empty($booking['catatan']) ? htmlspecialchars($booking['catatan']) : "-"; ?></p>
                        </div>

                        <div class="booking-crew-item full">
                            <label>Alat Disewa</label>
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

                    <div class="crew-action-bar">

                        <?php if ($booking['status'] == 'Pending'): ?>

                            <a
                                href="edit_booking.php?id=<?= $booking['id_booking']; ?>"
                                class="btn-edit">
                                Edit
                            </a>

                            <a
                                href="../process/admin/selesai_booking.php?id=<?= $booking['id_booking']; ?>"
                                class="btn-finish"
                                onclick="return confirm('Tandai booking ini sebagai selesai?')">
                                Selesai
                            </a>

                        <?php endif; ?>

                        <a
                            href="../process/admin/hapus_booking.php?id=<?= $booking['id_booking']; ?>"
                            class="btn-delete"
                            onclick="return confirm('Yakin ingin menghapus booking ini?')">
                            Hapus
                        </a>

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
<script src="../assets/js/admin.js"></script>

</body>
</html>
