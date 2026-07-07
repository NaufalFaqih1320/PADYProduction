<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

/* ======================================
   STATISTIK
====================================== */

$totalBarang = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM inventaris
"))['total'];

$totalStok = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT IFNULL(SUM(stok),0) total FROM inventaris
"))['total'];

$totalBaik = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM inventaris WHERE kondisi='Baik'
"))['total'];

$totalRusak = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM inventaris WHERE kondisi<>'Baik'
"))['total'];

/* ======================================
   QUERY INVENTARIS
====================================== */

$queryInventaris = mysqli_query($conn, "
    SELECT
        inventaris.*,
        kategori_inventaris.nama_kategori
    FROM inventaris
    LEFT JOIN kategori_inventaris
        ON inventaris.id_kategori = kategori_inventaris.id_kategori
    ORDER BY inventaris.created_at DESC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Owner</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/inventaris.css">
</head>
<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <div class="content-box">

            <h3>Inventaris PADY Production</h3>

            <div class="statistik inv-statistik">

                <div class="stat-card">
                    <h2><?= $totalBarang ?></h2>
                    <p>Total Barang</p>
                </div>

                <div class="stat-card">
                    <h2><?= $totalStok ?></h2>
                    <p>Total Stok</p>
                </div>

                <div class="stat-card">
                    <h2><?= $totalBaik ?></h2>
                    <p>Kondisi Baik</p>
                </div>

                <div class="stat-card">
                    <h2><?= $totalRusak ?></h2>
                    <p>Perlu Perhatian</p>
                </div>

            </div>

            <div class="toolbar">

                <div class="left-toolbar">

                    <div class="search-box">
                        <input
                            type="text"
                            id="searchInventaris"
                            placeholder="Cari nama barang, kategori, atau lokasi...">
                    </div>

                    <div class="filter">
                        <select id="filterKondisi">
                            <option value="">Semua Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="rusak ringan">Rusak Ringan</option>
                            <option value="rusak berat">Rusak Berat</option>
                        </select>
                    </div>

                </div>

            </div>

            <div class="inventory-grid">

                <?php if (mysqli_num_rows($queryInventaris) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($queryInventaris)): ?>

                        <?php
                            $kondisi = strtolower($row['kondisi']);

                            if ($kondisi == "baik") {
                                $statusClass = "selesai";
                            } elseif ($kondisi == "rusak ringan") {
                                $statusClass = "pending";
                            } else {
                                $statusClass = "rusak";
                            }
                        ?>

                        <div class="inventory-card"
                            data-search="<?= strtolower(
                                $row['nama_barang'] . ' ' .
                                ($row['nama_kategori'] ?? '') . ' ' .
                                $row['lokasi']
                            ); ?>"
                            data-kondisi="<?= $kondisi; ?>">

                            <div class="booking-header">
                                <div>
                                    <h3><?= htmlspecialchars($row['nama_barang']); ?></h3>
                                    <small><?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?></small>
                                </div>

                                <span class="status <?= $statusClass; ?>">
                                    <?= htmlspecialchars($row['kondisi']); ?>
                                </span>
                            </div>

                            <div class="booking-body">

                                <div class="booking-item">
                                    <label>Stok</label>
                                    <p><?= (int) $row['stok']; ?> unit</p>
                                </div>

                                <div class="booking-item">
                                    <label>Lokasi</label>
                                    <p><?= htmlspecialchars($row['lokasi']); ?></p>
                                </div>

                                <div class="booking-item full">
                                    <label>Keterangan</label>
                                    <p><?= !empty($row['keterangan']) ? htmlspecialchars($row['keterangan']) : "-"; ?></p>
                                </div>

                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="inventory-card">
                        <h3>Belum ada data inventaris.</h3>
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<script src="../assets/js/inventaris.js"></script>

</body>
</html>