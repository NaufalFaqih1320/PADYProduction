<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

/* ======================================
   FILTER PERIODE
====================================== */

$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : (int) date('n');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) date('Y');

/* ======================================
   RINGKASAN BOOKING PERIODE INI
====================================== */

$queryBooking = mysqli_query($conn, "
    SELECT *
    FROM booking
    WHERE MONTH(tanggal_acara) = $bulan
      AND YEAR(tanggal_acara) = $tahun
    ORDER BY tanggal_acara ASC
");

$totalBooking  = 0;
$totalSelesai  = 0;
$totalPending  = 0;
$totalPendapatan = 0;
$dataBooking   = [];

while ($row = mysqli_fetch_assoc($queryBooking)) {

    $totalBooking++;

    if ($row['status'] == 'Selesai') {
        $totalSelesai++;
        $totalPendapatan += (float) $row['total_harga'];
    } elseif ($row['status'] == 'Pending') {
        $totalPending++;
    }

    $dataBooking[] = $row;
}

/* ======================================
   BARANG PALING SERING DISEWA
====================================== */

$queryTopBarang = mysqli_query($conn, "
    SELECT
        i.nama_barang,
        SUM(bd.jumlah) AS total_disewa
    FROM booking_detail bd
    JOIN booking b ON bd.id_booking = b.id_booking
    JOIN inventaris i ON bd.id_inventaris = i.id_inventaris
    WHERE MONTH(b.tanggal_acara) = $bulan
      AND YEAR(b.tanggal_acara) = $tahun
    GROUP BY i.nama_barang
    ORDER BY total_disewa DESC
    LIMIT 5
");

$topBarang  = [];
$maxDisewa  = 1;

while ($row = mysqli_fetch_assoc($queryTopBarang)) {
    $topBarang[] = $row;
    $maxDisewa   = max($maxDisewa, (int) $row['total_disewa']);
}

/* ======================================
   FORMAT
====================================== */

function formatRupiah($angka): string {
    return "Rp " . number_format((float) $angka, 0, ',', '.');
}

$namaBulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Owner</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/laporan.css">
</head>
<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <div class="content-box" id="areaCetak">

            <div class="laporan-heading">

                <h3>Laporan Booking — <?= $namaBulan[$bulan] . ' ' . $tahun; ?></h3>

                <div class="laporan-actions no-print">

                    <form method="GET" class="filter-periode">

                        <select name="bulan">
                            <?php foreach ($namaBulan as $angka => $nama): ?>
                                <option value="<?= $angka; ?>" <?= $angka == $bulan ? 'selected' : ''; ?>>
                                    <?= $nama; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select name="tahun">
                            <?php for ($t = (int) date('Y'); $t >= (int) date('Y') - 3; $t--): ?>
                                <option value="<?= $t; ?>" <?= $t == $tahun ? 'selected' : ''; ?>>
                                    <?= $t; ?>
                                </option>
                            <?php endfor; ?>
                        </select>

                        <button type="submit">Tampilkan</button>

                    </form>

                    <button type="button" id="btnCetak" class="btn-cetak">Cetak / PDF</button>

                </div>

            </div>

            <div class="statistik">

                <div class="stat-card">
                    <h2><?= $totalBooking ?></h2>
                    <p>Total Booking</p>
                </div>

                <div class="stat-card">
                    <h2><?= $totalSelesai ?></h2>
                    <p>Selesai</p>
                </div>

                <div class="stat-card">
                    <h2><?= $totalPending ?></h2>
                    <p>Pending</p>
                </div>

                <div class="stat-card">
                    <h2><?= formatRupiah($totalPendapatan); ?></h2>
                    <p>Pendapatan (Selesai)</p>
                </div>

            </div>

            <div class="laporan-grid">

                <div class="laporan-panel">

                    <h4>Barang Paling Sering Disewa</h4>

                    <?php if (count($topBarang) > 0): ?>

                        <div class="bar-chart">

                            <?php foreach ($topBarang as $barang): ?>

                                <div class="bar-row">

                                    <span class="bar-label">
                                        <?= htmlspecialchars($barang['nama_barang']); ?>
                                    </span>

                                    <div class="bar-track">
                                        <div class="bar-fill"
                                            style="width: <?= ($barang['total_disewa'] / $maxDisewa) * 100; ?>%">
                                        </div>
                                    </div>

                                    <span class="bar-value">
                                        <?= $barang['total_disewa']; ?> unit
                                    </span>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <p class="laporan-kosong">Belum ada data penyewaan di periode ini.</p>

                    <?php endif; ?>

                </div>

                <div class="laporan-panel">

                    <h4>Distribusi Status Booking</h4>

                    <div class="status-distribusi">

                        <div class="status-item">
                            <span class="dot selesai"></span>
                            Selesai
                            <strong><?= $totalSelesai; ?></strong>
                        </div>

                        <div class="status-item">
                            <span class="dot pending"></span>
                            Pending
                            <strong><?= $totalPending; ?></strong>
                        </div>

                    </div>

                </div>

            </div>

            <div class="laporan-tabel">

                <h4>Detail Booking Periode Ini</h4>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Acara</th>
                            <th>Client</th>
                            <th>Tanggal Acara</th>
                            <th>Status</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (count($dataBooking) > 0): ?>

                            <?php $no = 1; foreach ($dataBooking as $row): ?>

                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_acara']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_client']); ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal_acara'])); ?></td>
                                    <td>
                                        <span class="status <?= strtolower($row['status']); ?>">
                                            <?= htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><?= formatRupiah($row['total_harga']); ?></td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6">Tidak ada booking pada periode ini.</td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>

            </div>

        </div>

    </div>

</div>

<script src="../assets/js/laporan.js"></script>

</body>
</html>