<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

/* ======================================
   AMBIL BOOKING YANG MASIH PENDING
====================================== */

$queryBooking = mysqli_query($conn, "
    SELECT *
    FROM booking
    WHERE status = 'Pending'
    ORDER BY tanggal_acara ASC
");

$today = new DateTime('today');

$dataBooking  = [];
$totalHariIni = 0;
$totalBesok   = 0;
$totalMinggu  = 0;
$totalLewat   = 0;

while ($row = mysqli_fetch_assoc($queryBooking)) {

    $tanggalAcara = new DateTime($row['tanggal_acara']);
    $selisih      = (int) $today->diff($tanggalAcara)->format('%r%a');

    if ($selisih < 0) {
        $row['urgensi']     = 'lewat';
        $row['label']       = 'Terlewat ' . abs($selisih) . ' hari';
        $totalLewat++;
    } elseif ($selisih == 0) {
        $row['urgensi']     = 'hari-ini';
        $row['label']       = 'Hari Ini';
        $totalHariIni++;
    } elseif ($selisih == 1) {
        $row['urgensi']     = 'besok';
        $row['label']       = 'Besok';
        $totalBesok++;
    } elseif ($selisih <= 7) {
        $row['urgensi']     = 'minggu-ini';
        $row['label']       = $selisih . ' Hari Lagi';
        $totalMinggu++;
    } else {
        $row['urgensi']     = 'akan-datang';
        $row['label']       = $selisih . ' Hari Lagi';
    }

    $row['selisih'] = $selisih;

    // hanya tampilkan yang terlewat, dekat, atau dalam 7 hari
    if ($selisih <= 7) {
        $dataBooking[] = $row;
    }
}

/* ======================================
   FORMAT NOMOR WHATSAPP
====================================== */

function formatNoWa(string $no): string {
    $no = preg_replace('/\D/', '', $no);
    if (substr($no, 0, 1) === '0') {
        $no = '62' . substr($no, 1);
    }
    return $no;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Owner</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/reminder.css">
</head>
<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <div class="content-box">

            <h3>Reminder Acara Mendatang</h3>

            <div class="statistik">

                <div class="stat-card">
                    <h2><?= $totalLewat ?></h2>
                    <p>Terlewat</p>
                </div>

                <div class="stat-card">
                    <h2><?= $totalHariIni ?></h2>
                    <p>Hari Ini</p>
                </div>

                <div class="stat-card">
                    <h2><?= $totalBesok ?></h2>
                    <p>Besok</p>
                </div>

                <div class="stat-card">
                    <h2><?= $totalMinggu ?></h2>
                    <p>Dalam Minggu Ini</p>
                </div>

            </div>

            <div class="toolbar">

                <div class="left-toolbar">

                    <div class="search-box">
                        <input
                            type="text"
                            id="searchReminder"
                            placeholder="Cari nama acara atau client...">
                    </div>

                </div>

            </div>

            <div class="reminder-list">

                <?php if (count($dataBooking) > 0): ?>

                    <?php foreach ($dataBooking as $row): ?>

                        <?php $noWa = formatNoWa($row['no_hp']); ?>

                        <?php
                            $pesanWa = "Halo {$row['nama_client']}, kami dari PADY Production ingin mengingatkan "
                                . "acara \"{$row['nama_acara']}\" yang dijadwalkan pada "
                                . date('d/m/Y', strtotime($row['tanggal_acara']))
                                . " di {$row['lokasi']}. Mohon dipersiapkan ya, terima kasih.";
                        ?>

                        <div class="reminder-card"
                            data-search="<?= strtolower($row['nama_acara'] . ' ' . $row['nama_client']); ?>">

                            <div class="booking-header">

                                <div>
                                    <h3><?= htmlspecialchars($row['nama_acara']); ?></h3>
                                    <small><?= date('d F Y', strtotime($row['tanggal_acara'])); ?></small>
                                </div>

                                <span class="urgensi <?= $row['urgensi']; ?>">
                                    <?= $row['label']; ?>
                                </span>

                            </div>

                            <div class="booking-body">

                                <div class="booking-item">
                                    <label>Client</label>
                                    <p><?= htmlspecialchars($row['nama_client']); ?></p>
                                </div>

                                <div class="booking-item">
                                    <label>No. HP</label>
                                    <p><?= htmlspecialchars($row['no_hp']); ?></p>
                                </div>

                                <div class="booking-item full">
                                    <label>Lokasi</label>
                                    <p><?= htmlspecialchars($row['lokasi']); ?></p>
                                </div>

                            </div>

                            <div class="reminder-action">

                                <a
                                    href="https://wa.me/<?= $noWa; ?>?text=<?= rawurlencode($pesanWa); ?>"
                                    target="_blank"
                                    class="btn-finish">
                                    Ingatkan via WhatsApp
                                </a>

                                <a
                                    href="edit_booking.php?id=<?= $row['id_booking']; ?>"
                                    class="btn-edit">
                                    Lihat Detail
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="reminder-card">
                        <h3>Tidak ada acara mendatang dalam 7 hari ke depan.</h3>
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<script src="../assets/js/reminder.js"></script>

</body>
</html>