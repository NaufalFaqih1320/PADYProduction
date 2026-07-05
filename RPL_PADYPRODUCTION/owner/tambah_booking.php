<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

$queryInventaris = mysqli_query($conn,"SELECT * FROM inventaris ORDER BY nama_barang ASC");

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tambah Booking</title>

<link rel="stylesheet" href="../assets/css/tambah_booking.css">

</head>

<body>

<div class="container">

<div class="back">

<a href="dashboard.php">← Kembali</a>

</div>

<div class="card">

<h1>Tambah Booking</h1>

<form action="../process/booking/tambah_booking.php" method="POST">

<div class="grid">

<div>

<label>Nama Client</label>

<input
type="text"
name="nama_client"
required>

</div>

<div>

<label>Nama Acara</label>

<input
type="text"
name="nama_acara"
required>

</div>

<div>

<label>Tanggal Acara</label>

<input
type="date"
name="tanggal_acara"
required>

</div>

<div>

<label>Tanggal Pemasangan</label>

<input
type="date"
name="tanggal_pasang"
required>

</div>

</div>

<label>Lokasi Acara</label>

<input
type="text"
name="lokasi"
required>

<h3>Alat yang Disewa</h3>

<?php while($inv = mysqli_fetch_assoc($queryInventaris)) { ?>

<div class="inventory-card">

    <div>

        <h2><?= $inv['nama_barang']; ?></h2>

        <p>Tersedia : <?= $inv['stok']; ?></p>

    </div>

    <div
        class="qty-box"
        data-stock="<?= $inv['stok']; ?>">

        <button
            type="button"
            class="minus"
            disabled>
            -
        </button>

        <span class="jumlah">0</span>

        <button
            type="button"
            class="plus">
            +
        </button>

        <input
            type="hidden"
            name="inventaris[<?= $inv['id_inventaris']; ?>]"
            value="0">

    </div>

</div>

<?php } ?>

<div class="action">

<a href="dashboard.php" class="cancel">

Batal

</a>

<button type="submit">

Tambah Booking

</button>

</div>

</form>

</div>

</div>

<script src="../assets/js/tambah-booking.js"></script>

</body>

</html>