<?php

require_once "../config/database.php";
require_once "../config/auth.php";

crewOnly();

$currentTab = 'jadwal';

/* ===========================
   AMBIL DATA BOOKING (AGENDA / LIST)
=========================== */

$queryJadwal = mysqli_query($conn, "
    SELECT *
    FROM booking
    ORDER BY tanggal_acara ASC, waktu_acara ASC
");

$today = date("Y-m-d");

/* ===========================
   PARAM BULAN & TAHUN (KALENDER)
=========================== */

$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : (int) date('n');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) date('Y');

if ($bulan < 1 || $bulan > 12) {
    $bulan = (int) date('n');
}

$namaBulan = [
    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
    5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
    9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
];

$timestampAwal = mktime(0, 0, 0, $bulan, 1, $tahun);
$totalHari      = (int) date('t', $timestampAwal);
$hariPertama    = (int) date('N', $timestampAwal); // 1 = Senin ... 7 = Minggu

$bulanPrev = $bulan - 1;
$tahunPrev = $tahun;

if ($bulanPrev < 1) {
    $bulanPrev = 12;
    $tahunPrev--;
}

$bulanNext = $bulan + 1;
$tahunNext = $tahun;

if ($bulanNext > 12) {
    $bulanNext = 1;
    $tahunNext++;
}

/* ===========================
   BOOKING UNTUK BULAN YANG DIPILIH
   (dikelompokkan per tanggal, untuk kalender)
=========================== */

$awalBulanStr  = date("Y-m-d", $timestampAwal);
$akhirBulanStr = date("Y-m-d", mktime(0, 0, 0, $bulan, $totalHari, $tahun));

$queryBulanIni = mysqli_query($conn, "
    SELECT *
    FROM booking
    WHERE tanggal_acara BETWEEN '$awalBulanStr' AND '$akhirBulanStr'
    ORDER BY tanggal_acara ASC, waktu_acara ASC
");

$eventsByDay = [];

while ($row = mysqli_fetch_assoc($queryBulanIni)) {

    $tgl = (int) date('j', strtotime($row['tanggal_acara']));

    if (!isset($eventsByDay[$tgl])) {
        $eventsByDay[$tgl] = [];
    }

    $eventsByDay[$tgl][] = $row;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal - Crew</title>
    <link rel="stylesheet" href="../assets/css/crew.css">
</head>
<body>

<?php include "../includes/layout/topbar.php"; ?>

<?php include "../includes/components/crew_tabs.php"; ?>

<div class="crew-content">

    <div class="crew-page-header">
        <h2>Jadwal</h2>
    </div>

    <div class="jadwal-subtabs">
        <div class="jadwal-subtab active" data-target="tabList">Daftar Jadwal</div>
        <div class="jadwal-subtab" data-target="tabKalender">Kalender</div>
    </div>

    <!-- ===========================
         TAB 1 : DAFTAR JADWAL (AGENDA)
    =========================== -->

    <div class="jadwal-tabcontent" id="tabList" style="display:block;">

        <div class="crew-toolbar">

            <div class="crew-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="text"
                    id="searchJadwalCrew"
                    placeholder="Cari nama acara, client, atau lokasi...">
            </div>

            <div class="crew-filter-pills" id="statusPillsJadwal">
                <button type="button" class="pill active" data-status="semua">Semua</button>
                <button type="button" class="pill" data-status="pending">Pending</button>
                <button type="button" class="pill" data-status="selesai">Selesai</button>
            </div>

        </div>

        <?php if (mysqli_num_rows($queryJadwal) > 0): ?>

            <?php
            $lastDate = null;

            while ($booking = mysqli_fetch_assoc($queryJadwal)):

                $tanggal = $booking['tanggal_acara'];

                if ($tanggal !== $lastDate):
                    $lastDate = $tanggal;
                    ?>

                    <div class="jadwal-date-group">
                        <?= date("l, d F Y", strtotime($tanggal)); ?>
                    </div>

                <?php endif; ?>

                <?php
                if ($tanggal === $today) {
                    $badgeClass = "hari-ini";
                    $badgeText  = "Hari Ini";
                } elseif ($tanggal < $today) {
                    $badgeClass = "terlewat";
                    $badgeText  = "Terlewat";
                } else {
                    $badgeClass = "mendatang";
                    $badgeText  = "Mendatang";
                }
                ?>

                <div class="jadwal-crew-card"
                    data-search="<?= strtolower(
                        $booking['nama_acara'] . ' ' .
                        $booking['nama_client'] . ' ' .
                        $booking['lokasi']
                    ); ?>"
                    data-status="<?= strtolower($booking['status']); ?>">

                    <div class="jadwal-crew-time">
                        <?= htmlspecialchars($booking['waktu_acara']); ?>
                        <span><?= date("d/m", strtotime($tanggal)); ?></span>
                    </div>

                    <div class="jadwal-crew-body">

                        <span class="jadwal-badge <?= $badgeClass; ?>">
                            <?= $badgeText; ?>
                        </span>

                        <h3><?= htmlspecialchars($booking['nama_acara']); ?></h3>

                        <p><strong>Client:</strong> <?= htmlspecialchars($booking['nama_client']); ?></p>
                        <p><strong>No. HP:</strong> <?= htmlspecialchars($booking['no_hp']); ?></p>
                        <p><strong>Lokasi:</strong> <?= htmlspecialchars($booking['lokasi']); ?></p>

                        <span class="status <?= strtolower($booking['status']); ?>">
                            <?= htmlspecialchars($booking['status']); ?>
                        </span>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="jadwal-crew-card">
                <p>Belum ada jadwal booking.</p>
            </div>

        <?php endif; ?>

    </div>

    <!-- ===========================
         TAB 2 : KALENDER
    =========================== -->

    <div class="jadwal-tabcontent" id="tabKalender" style="display:none;">

        <div class="calendar-nav">

            <a href="?bulan=<?= $bulanPrev; ?>&tahun=<?= $tahunPrev; ?>#tabKalender">&larr; Sebelumnya</a>

            <h3><?= $namaBulan[$bulan]; ?> <?= $tahun; ?></h3>

            <a href="?bulan=<?= $bulanNext; ?>&tahun=<?= $tahunNext; ?>#tabKalender">Berikutnya &rarr;</a>

        </div>

        <div class="calendar-grid">

            <div class="calendar-head">Sen</div>
            <div class="calendar-head">Sel</div>
            <div class="calendar-head">Rab</div>
            <div class="calendar-head">Kam</div>
            <div class="calendar-head">Jum</div>
            <div class="calendar-head">Sab</div>
            <div class="calendar-head">Min</div>

            <?php for ($i = 1; $i < $hariPertama; $i++): ?>
                <div class="calendar-day empty"></div>
            <?php endfor; ?>

            <?php for ($hari = 1; $hari <= $totalHari; $hari++):

                $tanggalIni = sprintf("%04d-%02d-%02d", $tahun, $bulan, $hari);
                $isToday    = ($tanggalIni === $today);
                $events     = $eventsByDay[$hari] ?? [];
                $hasEvent   = count($events) > 0;

                $eventsJson = htmlspecialchars(json_encode(array_map(function ($e) {
                    return [
                        'nama_acara'  => $e['nama_acara'],
                        'nama_client' => $e['nama_client'],
                        'lokasi'      => $e['lokasi'],
                        'waktu_acara' => $e['waktu_acara'],
                        'status'      => $e['status'],
                        'catatan'     => $e['catatan'],
                    ];
                }, $events)), ENT_QUOTES);
                ?>

                <div
                    class="calendar-day <?= $isToday ? 'today' : ''; ?> <?= $hasEvent ? 'has-event' : ''; ?>"
                    data-tanggal="<?= date("l, d F Y", strtotime($tanggalIni)); ?>"
                    data-events='<?= $eventsJson; ?>'>

                    <div class="day-number"><?= $hari; ?></div>

                    <?php foreach (array_slice($events, 0, 2) as $ev): ?>
                        <div class="event-pill"><?= htmlspecialchars($ev['nama_acara']); ?></div>
                    <?php endforeach; ?>

                    <?php if (count($events) > 2): ?>
                        <div class="event-more">+<?= count($events) - 2; ?> lainnya</div>
                    <?php endif; ?>

                </div>

            <?php endfor; ?>

        </div>

        <div id="calendarDetailCrew"></div>

    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="../assets/js/crew.js"></script>

</body>
</html>