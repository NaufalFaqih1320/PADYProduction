<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

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

/* Bulan sebelumnya & berikutnya (untuk tombol navigasi) */

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
   AMBIL BOOKING UNTUK BULAN YANG DIPILIH
   dikelompokkan per tanggal (untuk kalender)
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

    <title>Jadwal Booking</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <style>

        /* ==========================
           JADWAL BOOKING (khusus halaman ini)
        ========================== */

        .date-group{
            margin: 30px 0 15px;
            font-size: 15px;
            font-weight: 600;
            color: #B88A44;
            border-left: 4px solid #FF6B35;
            padding-left: 10px;
        }

        .date-group:first-of-type{
            margin-top: 0;
        }

        .jadwal-card{
            display: flex;
            gap: 20px;
            border: 1px solid #ECECEC;
            border-radius: 16px;
            padding: 20px 22px;
            margin-bottom: 16px;
            transition: .3s;
        }

        .jadwal-card:hover{
            box-shadow: 0 8px 18px rgba(0,0,0,.08);
        }

        .jadwal-time{
            min-width: 90px;
            text-align: center;
            font-weight: 600;
            color: #FF6B35;
            border-right: 1px solid #eee;
            padding-right: 18px;
        }

        .jadwal-time span{
            display: block;
            font-size: 12px;
            font-weight: 400;
            color: #999;
            margin-top: 4px;
        }

        .jadwal-body{
            flex: 1;
        }

        .jadwal-body h3{
            margin-bottom: 6px;
        }

        .jadwal-body p{
            color: #555;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .jadwal-badge{
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .jadwal-badge.hari-ini{ background:#FFF3CD; color:#856404; }
        .jadwal-badge.terlewat{ background:#F8D7DA; color:#721C24; }
        .jadwal-badge.mendatang{ background:#D4EDDA; color:#155724; }

        /* ==========================
           KALENDER
        ========================== */

        .calendar-nav{
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .calendar-nav h3{
            font-size: 18px;
            color: #333;
        }

        .calendar-nav a{
            text-decoration: none;
            background: #F8F8F8;
            border: 1px solid #ddd;
            padding: 8px 16px;
            border-radius: 10px;
            color: #333;
            font-weight: 500;
            transition: .2s;
        }

        .calendar-nav a:hover{
            background: #FF6B35;
            border-color: #FF6B35;
            color: #fff;
        }

        .calendar-grid{
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }

        .calendar-head{
            text-align: center;
            font-weight: 600;
            font-size: 13px;
            color: #999;
            padding-bottom: 8px;
        }

        .calendar-day{
            min-height: 90px;
            border: 1px solid #ECECEC;
            border-radius: 12px;
            padding: 8px;
            font-size: 13px;
            cursor: default;
            transition: .2s;
        }

        .calendar-day.empty{
            background: #FAFAFA;
            border: none;
        }

        .calendar-day.has-event{
            cursor: pointer;
            border-color: #FFD3B8;
        }

        .calendar-day.has-event:hover{
            box-shadow: 0 5px 12px rgba(0,0,0,.08);
        }

        .calendar-day.today{
            border: 2px solid #FF6B35;
        }

        .calendar-day .day-number{
            font-weight: 600;
            margin-bottom: 6px;
        }

        .calendar-day.today .day-number{
            color: #FF6B35;
        }

        .event-pill{
            background: #FFF3CD;
            color: #856404;
            border-radius: 6px;
            padding: 2px 6px;
            font-size: 11px;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event-more{
            font-size: 11px;
            color: #B88A44;
            font-weight: 600;
        }

        #calendarDetail{
            margin-top: 25px;
        }

        #calendarDetail h4{
            margin-bottom: 12px;
            color: #B88A44;
        }

        @media(max-width:768px){

            .calendar-grid{
                grid-template-columns: repeat(7, 1fr);
                gap: 4px;
            }

            .calendar-day{
                min-height: 60px;
                padding: 4px;
                font-size: 11px;
            }

        }

    </style>

</head>
<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <div class="content-box">

            <h3>Jadwal Booking</h3>

            <div class="tabs">

                <div class="tab active" data-target="tabList">Daftar Jadwal</div>
                <div class="tab" data-target="tabKalender">Kalender</div>

            </div>

            <!-- ===========================
                 TAB 1 : DAFTAR JADWAL (AGENDA)
            =========================== -->

            <div class="tab-content" id="tabList" style="display:block;">

                <div class="toolbar">

                    <div class="left-toolbar">

                        <div class="search-box">

                            <input
                                type="text"
                                id="searchJadwal"
                                placeholder="Cari nama acara, client, atau lokasi...">

                        </div>

                        <div class="filter">

                            <select id="filterStatusJadwal">

                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="selesai">Selesai</option>

                            </select>

                        </div>

                    </div>

                    <a href="tambah_booking.php" class="btn-add">
                        + Tambah Booking
                    </a>

                </div>

                <?php if (mysqli_num_rows($queryJadwal) > 0): ?>

                    <?php
                    $lastDate = null;

                    while ($booking = mysqli_fetch_assoc($queryJadwal)):

                        $tanggal = $booking['tanggal_acara'];

                        if ($tanggal !== $lastDate):
                            $lastDate = $tanggal;
                            ?>

                            <div class="date-group">
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

                        <div class="jadwal-card"
                            data-search="<?= strtolower(
                                $booking['nama_acara'] . ' ' .
                                $booking['nama_client'] . ' ' .
                                $booking['lokasi']
                            ); ?>"
                            data-status="<?= strtolower($booking['status']); ?>">

                            <div class="jadwal-time">

                                <?= htmlspecialchars($booking['waktu_acara']); ?>

                                <span><?= date("d/m", strtotime($tanggal)); ?></span>

                            </div>

                            <div class="jadwal-body">

                                <span class="jadwal-badge <?= $badgeClass; ?>">
                                    <?= $badgeText; ?>
                                </span>

                                <h3><?= htmlspecialchars($booking['nama_acara']); ?></h3>

                                <p><strong>Client:</strong> <?= htmlspecialchars($booking['nama_client']); ?></p>

                                <p><strong>Lokasi:</strong> <?= htmlspecialchars($booking['lokasi']); ?></p>

                                <span class="status <?= strtolower($booking['status']); ?>">
                                    <?= htmlspecialchars($booking['status']); ?>
                                </span>

                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="jadwal-card">
                        <p>Belum ada jadwal booking.</p>
                    </div>

                <?php endif; ?>

            </div>

            <!-- ===========================
                 TAB 2 : KALENDER
            =========================== -->

            <div class="tab-content" id="tabKalender" style="display:none;">

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

                    <?php
                    /* Sel kosong sebelum tanggal 1 (hariPertama: 1=Senin) */

                    for ($i = 1; $i < $hariPertama; $i++):
                        ?>
                        <div class="calendar-day empty"></div>
                    <?php endfor; ?>

                    <?php for ($hari = 1; $hari <= $totalHari; $hari++):

                        $tanggalIni = sprintf("%04d-%02d-%02d", $tahun, $bulan, $hari);
                        $isToday    = ($tanggalIni === $today);
                        $events     = $eventsByDay[$hari] ?? [];
                        $hasEvent   = count($events) > 0;

                        $eventsJson = htmlspecialchars(json_encode(array_map(function ($e) {
                            return [
                                'nama_acara'   => $e['nama_acara'],
                                'nama_client'  => $e['nama_client'],
                                'lokasi'       => $e['lokasi'],
                                'waktu_acara'  => $e['waktu_acara'],
                                'status'       => $e['status'],
                                'catatan'      => $e['catatan'],
                            ];
                        }, $events)), ENT_QUOTES);
                        ?>

                        <div
                            class="calendar-day <?= $isToday ? 'today' : ''; ?> <?= $hasEvent ? 'has-event' : ''; ?>"
                            data-tanggal="<?= date("l, d F Y", strtotime($tanggalIni)); ?>"
                            data-events='<?= $eventsJson; ?>'>

                            <div class="day-number"><?= $hari; ?></div>

                            <?php foreach (array_slice($events, 0, 2) as $ev): ?>

                                <div class="event-pill">
                                    <?= htmlspecialchars($ev['nama_acara']); ?>
                                </div>

                            <?php endforeach; ?>

                            <?php if (count($events) > 2): ?>

                                <div class="event-more">+<?= count($events) - 2; ?> lainnya</div>

                            <?php endif; ?>

                        </div>

                    <?php endfor; ?>

                </div>

                <div id="calendarDetail"></div>

            </div>

        </div>

    </div>

</div>

<script>

    /* ================================
       SWITCH TAB (List <-> Kalender)
    ================================ */

    const tabs = document.querySelectorAll(".tab");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {

        tab.addEventListener("click", () => {

            tabs.forEach(t => t.classList.remove("active"));
            contents.forEach(c => c.style.display = "none");

            tab.classList.add("active");
            document.getElementById(tab.dataset.target).style.display = "block";

        });

    });

    /* Jika URL mengandung #tabKalender (habis klik navigasi bulan), buka tab kalender */

    if (window.location.hash === "#tabKalender") {

        tabs.forEach(t => t.classList.remove("active"));
        contents.forEach(c => c.style.display = "none");

        document.querySelector('.tab[data-target="tabKalender"]').classList.add("active");
        document.getElementById("tabKalender").style.display = "block";

    }

    /* ================================
       SEARCH & FILTER (Daftar Jadwal)
    ================================ */

    const searchInput = document.getElementById("searchJadwal");
    const filterStatus = document.getElementById("filterStatusJadwal");
    const jadwalCards = document.querySelectorAll(".jadwal-card");

    function applyFilter() {

        const keyword = searchInput.value.toLowerCase();
        const status = filterStatus.value;

        jadwalCards.forEach(card => {

            const matchSearch = card.dataset.search
                ? card.dataset.search.includes(keyword)
                : true;

            const matchStatus = status
                ? card.dataset.status === status
                : true;

            card.style.display = (matchSearch && matchStatus) ? "" : "none";

        });

    }

    if (searchInput) searchInput.addEventListener("keyup", applyFilter);
    if (filterStatus) filterStatus.addEventListener("change", applyFilter);

    /* ================================
       KLIK TANGGAL DI KALENDER
    ================================ */

    const calendarDays = document.querySelectorAll(".calendar-day.has-event");
    const detailBox = document.getElementById("calendarDetail");

    calendarDays.forEach(day => {

        day.addEventListener("click", () => {

            const events = JSON.parse(day.dataset.events);
            const tanggal = day.dataset.tanggal;

            let html = `<h4>${tanggal}</h4>`;

            events.forEach(ev => {

                html += `
                    <div class="jadwal-card">
                        <div class="jadwal-time">
                            ${ev.waktu_acara}
                        </div>
                        <div class="jadwal-body">
                            <h3>${ev.nama_acara}</h3>
                            <p><strong>Client:</strong> ${ev.nama_client}</p>
                            <p><strong>Lokasi:</strong> ${ev.lokasi}</p>
                            <span class="status ${ev.status.toLowerCase()}">${ev.status}</span>
                        </div>
                    </div>
                `;

            });

            detailBox.innerHTML = html;
            detailBox.scrollIntoView({ behavior: "smooth", block: "nearest" });

        });

    });

</script>

</body>
</html>