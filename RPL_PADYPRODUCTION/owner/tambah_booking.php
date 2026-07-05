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

<form action="<?= BASE_URL ?>process/booking/tambah_booking.php" method="POST">

<div class="full">

    <label>Nama Acara</label>

    <input
        type="text"
        name="nama_acara"
        required>

</div>

<div class="grid">

    <div>

        <label>Nama Client</label>

        <input
            type="text"
            name="nama_client"
            required>

    </div>

    <div>

        <label>Email Client</label>

        <input
            type="email"
            name="email_client"
            required>

    </div>

</div>

<div class="grid">

    <div>

        <label>No. HP</label>

        <input
            type="text"
            name="no_hp"
            required>

    </div>

    <div>

        <label>Waktu Acara</label>

        <input
            type="time"
            name="waktu_acara"
            required>

    </div>

</div>

<div class="grid">

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
    name="tanggal_pemasangan"
    required>

    </div>

</div>

<div class="full">

    <label>Lokasi Acara</label>

    <input
        type="text"
        name="lokasi"
        required>

</div>

<div class="full">

    <label>Catatan</label>

    <textarea
        name="catatan" placeholder="Tambahkan catatan jika diperlukan"></textarea>

</div>

<h3>Alat yang Disewa</h3>

<?php while($barang = mysqli_fetch_assoc($queryInventaris)) : ?>

<div class="inventory-card">

    <div class="inventory-content">

        <div class="inventory-left">

            <h4><?= $barang['nama_barang']; ?></h4>

            <p>
                Tersedia :
                <strong><?= $barang['stok']; ?></strong> unit
            </p>

        </div>

        <div class="inventory-right">

            <button
                type="button"
                class="minus"
                disabled>
                −
            </button>

            <span class="jumlah">0</span>

            <button
                type="button"
                class="plus"
                data-max="<?= $barang['stok']; ?>">
                +
            </button>

            <input
                type="hidden"
                class="qty-input"
                name="inventaris[<?= $barang['id_inventaris']; ?>]"
                value="0">

        </div>

    </div>

</div>

<?php endwhile; ?>

<div class="action">

<a href="dashboard.php" class="cancel">

Batal

</a>

<button type="submit" class="btn btn-primary">

Tambah Booking

</button>

</div>

</form>

</div>

</div>

<script src="../assets/js/tambah_booking.js"></script>

</body>

</html>