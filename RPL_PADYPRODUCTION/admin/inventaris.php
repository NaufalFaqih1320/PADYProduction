<?php

require_once "../config/database.php";
require_once "../config/auth.php";

adminOnly();

$currentTab = 'inventaris';

/* ======================================
   DAFTAR KATEGORI (UNTUK PILL FILTER & FORM)
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
    <title>Kelola Inventaris - Admin</title>
    <link rel="stylesheet" href="../assets/css/crew.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "../includes/layout/topbar.php"; ?>

<?php include "../includes/components/admin_tabs.php"; ?>

<div class="crew-content">

    <div class="crew-page-header">

        <h2>Daftar Inventaris</h2>

        <button type="button" class="btn-add-inventaris" data-open-modal="modalTambahInventaris">
            + Tambah Inventaris
        </button>

    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="admin-alert success"><?= htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="admin-alert error"><?= htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

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

                        <div class="user-table-actions">

                            <button
                                type="button"
                                class="btn-edit btn-edit-inventaris"
                                data-id="<?= $row['id_inventaris']; ?>"
                                data-nama-barang="<?= htmlspecialchars($row['nama_barang'], ENT_QUOTES); ?>"
                                data-id-kategori="<?= $row['id_kategori'] ?? ''; ?>"
                                data-stok="<?= $tersedia; ?>"
                                data-kondisi="<?= $row['kondisi']; ?>"
                                data-lokasi="<?= htmlspecialchars($row['lokasi'] ?? '', ENT_QUOTES); ?>"
                                data-keterangan="<?= htmlspecialchars($row['keterangan'] ?? '', ENT_QUOTES); ?>">
                                Edit
                            </button>

                            <a
                                href="../process/admin/hapus_inventaris.php?id=<?= $row['id_inventaris']; ?>"
                                class="btn-delete"
                                onclick="return confirm('Yakin ingin menghapus <?= htmlspecialchars(addslashes($row['nama_barang'])); ?>?')">
                                Hapus
                            </a>

                        </div>

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
<div class="modal-overlay" id="modalTambahInventaris">

    <div class="modal-box">

        <div class="modal-header">
            <h3>Tambah Inventaris</h3>
            <button type="button" class="modal-close" data-close-modal="modalTambahInventaris">&times;</button>
        </div>

        <form action="../process/admin/tambah_inventaris.php" method="POST">

            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori">
                    <option value="">Tanpa kategori</option>
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
                <button type="button" class="btn-cancel" data-close-modal="modalTambahInventaris">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>

        </form>

    </div>

</div>

<!-- MODAL EDIT INVENTARIS -->
<div class="modal-overlay" id="modalEditInventaris">

    <div class="modal-box">

        <div class="modal-header">
            <h3>Edit Inventaris</h3>
            <button type="button" class="modal-close" data-close-modal="modalEditInventaris">&times;</button>
        </div>

        <form action="../process/admin/edit_inventaris.php" method="POST">

            <input type="hidden" name="id_inventaris">

            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori">
                    <option value="">Tanpa kategori</option>
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
                    <label>Stok</label>
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
                <button type="button" class="btn-cancel" data-close-modal="modalEditInventaris">Batal</button>
                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </div>

        </form>

    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="../assets/js/admin.js"></script>

</body>
</html>
