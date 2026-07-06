<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

/* ======================================
   STATISTIK
====================================== */

$totalBarang = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM inventaris
"))['total'];

$totalStok = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT IFNULL(SUM(stok),0) total
FROM inventaris
"))['total'];

$totalBaik = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM inventaris
WHERE kondisi='Baik'
"))['total'];

$totalRusak = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM inventaris
WHERE kondisi<>'Baik'
"))['total'];

/* ======================================
   PENCARIAN
====================================== */

$keyword = "";

if (isset($_GET['search'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['search']);
}

/* ======================================
   QUERY INVENTARIS
====================================== */

$sql = "
SELECT
inventaris.*,
kategori_inventaris.nama_kategori
FROM inventaris

LEFT JOIN kategori_inventaris
ON inventaris.id_kategori = kategori_inventaris.id_kategori
";

if ($keyword != "") {

    $sql .= "
WHERE
inventaris.nama_barang LIKE '%$keyword%'
OR kategori_inventaris.nama_kategori LIKE '%$keyword%'
OR inventaris.lokasi LIKE '%$keyword%'
";
}

$sql .= "
ORDER BY inventaris.created_at DESC
";

$queryInventaris = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Inventaris Owner</title>

    <link rel="stylesheet"
        href="../assets/css/dashboard.css">

    <link rel="stylesheet"
        href="../assets/css/inventaris.css">

</head>

<body>
    <div class="wrapper">
        <?php include "../includes/layout/sidebar.php"; ?>
        <div class="main-content">
            <?php include "../includes/layout/topbar.php"; ?>
            <div class="content-box">
                <h2>Inventaris PADY Production</h2>
                <div class="inventory-summary">
                    <div class="summary-card">
                        <h4>Total Barang</h4>
                        <h2><?= $totalBarang ?></h2>
                    </div>
                    <div class="summary-card">
                        <h4>Total Stok</h4>
                        <h2><?= $totalStok ?></h2>
                    </div>
                    <div class="summary-card">
                        <h4>Kondisi Baik</h4>
                        <h2><?= $totalBaik ?></h2>
                    </div>
                    <div class="summary-card">
                        <h4>Rusak</h4>
                        <h2><?= $totalRusak ?></h2>
                    </div>
                </div>
                <div class="inventory-toolbar">
                    <form method="GET">
                        <input
                            type="text
                            name=" search"
                            placeholder="Cari barang..."
                            value="<?= htmlspecialchars($keyword) ?>">
                        <button type="submit">
                            Cari
                        </button>
                    </form>
                </div>
                <div class="inventory-table">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <!-- <th>Foto</th> -->
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Kondisi</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (mysqli_num_rows($queryInventaris) > 0):
                                while ($row = mysqli_fetch_assoc($queryInventaris)):
                            ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <!-- <td>
                                            <?php

                                            $foto = "../uploads/inventaris/" . $row['foto'];

                                            if (!empty($row['foto']) && file_exists($foto)) {
                                            ?>

                                                <img src="<?= $foto ?>">

                                            <?php } else { ?>

                                                <img src="../assets/images/no-image.png">

                                            <?php } ?>
                                        </td> -->
                                        <td>
                                            <?= htmlspecialchars($row['nama_barang']) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($row['nama_kategori']) ?>
                                        </td>
                                        <td>
                                            <?= $row['stok'] ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['kondisi'] == "Baik") {

                                                echo "<span class='badge-good'>Baik</span>";
                                            } elseif ($row['kondisi'] == "Rusak Ringan") {

                                                echo "<span class='badge-warning'>Rusak Ringan</span>";
                                            } else {

                                                echo "<span class='badge-danger'>" . $row['kondisi'] . "</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($row['lokasi']) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($row['keterangan']) ?>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="8">
                                        Belum ada data inventaris.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/inventaris.js"></script>
</body>

</html>