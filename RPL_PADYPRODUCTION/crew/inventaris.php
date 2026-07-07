<?php

require_once "../config/database.php";
require_once "../config/auth.php";

crewOnly();

$currentTab = 'inventaris';

/* ======================================
   DAFTAR KATEGORI (UNTUK PILL FILTER)
====================================== */

$queryKategori = mysqli_query($conn, "
    SELECT id_kategori, nama_kategori
    FROM kategori_inventaris
    ORDER BY nama_kategori ASC
");

/* ======================================
   QUERY INVENTARIS + HITUNG SEDANG DISEWA
====================================== */

$queryInventaris = mysqli_query($conn, "
    SELECT
        i.*,
        k.nama_kategori,
        IFNULL((
            SELECT SUM(bd.jumlah)
            FROM booking_detail bd
            JOIN booking b ON bd.id_booking = b.id_booking
            WHERE bd.id_inventaris = i.id_inventaris
              AND b.status = 'Pending'
        ), 0) AS sedang_disewa
    FROM inventaris i
    LEFT JOIN kategori_inventaris k
        ON i.id_kategori = k.id_kategori
    ORDER BY i.nama_barang ASC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Inventaris - Crew</title>
    <link rel="stylesheet" href="../assets/css/crew.css">
</head>
<body>

<?php include "../includes/layout/topbar.php"; ?>

<?php include "../includes/components/crew_tabs.php"; ?>

<div class="crew-content">

    <div class="crew-page-header">

        <h2>Daftar Inventaris</h2>

        <button type="button" class="btn-add-inventaris" id="btnTambahInventaris">
            + Tambah Inventaris
        </button>

    </div>

    <div class="kategori-pills" id="kategoriPills">

        <button type="button" class="pill active" data-kategori="semua">Semua</button>

        <?php while ($kat = mysqli_fetch_assoc($queryKategori)): ?>

            <button type="button" class="pill" data-kategori="<?= $kat['id_kategori']; ?>">
                <?= htmlspecialchars($kat['nama_kategori']); ?>
            </button>

        <?php endwhile; ?>

    </div>

    <div class="inventaris-list" id="inventarisList">

        <?php if (mysqli_num_rows($queryInventaris) > 0): ?>

            <?php while ($row = mysqli_fetch_assoc($queryInventaris)): ?>

                <?php
                    $tersedia    = (int) $row['stok'];
                    $disewa      = (int) $row['sedang_disewa'];
                    $totalStok   = $tersedia + $disewa;
                ?>

                <div class="inventaris-card" data-kategori="<?= $row['id_kategori'] ?? ''; ?>">

                    <div class="inventaris-card-header">

                        <h3><?= htmlspecialchars($row['nama_barang']); ?></h3>

                        <a href="detail_inventaris.php?id=<?= $row['id_inventaris']; ?>" class="btn-detail">
                            <i class="fa-solid fa-pen"></i> Detail
                        </a>

                    </div>

                    <div class="inventaris-stats">

                        <div class="stat">
                            <span class="stat-label">Total Stok</span>
                            <span class="stat-value"><?= $totalStok; ?> <small>unit</small></span>
                        </div>

                        <div class="stat">
                            <span class="stat-label">Sedang Disewa</span>
                            <span class="stat-value disewa"><?= $disewa; ?> <small>unit</small></span>
                        </div>

                        <div class="stat">
                            <span class="stat-label">Tersedia</span>
                            <span class="stat-value tersedia"><?= $tersedia; ?> <small>unit</small></span>
                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="inventaris-card">
                <h3>Belum ada data inventaris.</h3>
            </div>

        <?php endif; ?>

    </div>

</div>

<!-- MODAL TAMBAH INVENTARIS -->
<div class="modal-overlay" id="modalTambah">

    <div class="modal-box">

        <div class="modal-header">
            <h3>Tambah Inventaris</h3>
            <button type="button" class="modal-close" id="btnTutupModal">&times;</button>
        </div>

        <form action="../process/inventaris/tambah_inventaris.php" method="POST">

            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" required>
                    <?php mysqli_data_seek($queryKategori, 0); ?>
                    <?php while ($kat = mysqli_fetch_assoc($queryKategori)): ?>
                        <option value="<?= $kat['id_kategori']; ?>">
                            <?= htmlspecialchars($kat['nama_kategori']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" min="0" required>
                </div>

                <div class="form-group">
                    <label>Kondisi</label>
                    <select name="kondisi" required>
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>

            </div>

            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" required>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan"></textarea>
            </div>

            <div class="modal-action">
                <button type="button" class="btn-cancel" id="btnBatalModal">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>

        </form>

    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="../assets/js/crew.js"></script>

</body>
</html>