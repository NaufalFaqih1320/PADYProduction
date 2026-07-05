<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

/* ======================================
   CEK ID BOOKING
====================================== */

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id_booking = (int)$_GET['id'];

/* ======================================
   AMBIL DATA BOOKING
====================================== */

$queryBooking = mysqli_query($conn, "
    SELECT *
    FROM booking
    WHERE id_booking = '$id_booking'
");

if (mysqli_num_rows($queryBooking) == 0) {
    header("Location: dashboard.php");
    exit;
}

$booking = mysqli_fetch_assoc($queryBooking);

/* ======================================
   AMBIL INVENTARIS
====================================== */

$queryInventaris = mysqli_query($conn,"
    SELECT *
    FROM inventaris
    ORDER BY nama_barang ASC
");

/* ======================================
   AMBIL DETAIL BOOKING
====================================== */

$selected = [];

$queryDetail = mysqli_query($conn,"
    SELECT *
    FROM booking_detail
    WHERE id_booking='$id_booking'
");

while($row = mysqli_fetch_assoc($queryDetail)){
    $selected[$row['id_inventaris']] = $row['jumlah'];
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Booking</title>

<link rel="stylesheet" href="../assets/css/tambah_booking.css">

</head>

<body>

<div class="container">

<div class="back">

<a href="dashboard.php">← Kembali</a>

</div>

<div class="card">

<h1>Edit Booking</h1>

<form action="<?= BASE_URL ?>process/booking/edit_booking.php" method="POST">

<input
    type="hidden"
    name="id_booking"
    value="<?= $booking['id_booking']; ?>">

<div class="full">

<label>Nama Acara</label>

<input
    type="text"
    name="nama_acara"
    value="<?= htmlspecialchars($booking['nama_acara']); ?>"
    required>

</div>

<div class="grid">

<div>

<label>Nama Client</label>

<input
    type="text"
    name="nama_client"
    value="<?= htmlspecialchars($booking['nama_client']); ?>"
    required>

</div>

<div>

<label>Email Client</label>

<input
    type="email"
    name="email_client"
    value="<?= htmlspecialchars($booking['email_client']); ?>"
    required>

</div>

</div>

<div class="grid">

<div>

<label>No. HP</label>

<input
    type="text"
    name="no_hp"
    value="<?= htmlspecialchars($booking['no_hp']); ?>"
    required>

</div>

<div>

<label>Waktu Acara</label>

<input
    type="time"
    name="waktu_acara"
    value="<?= $booking['waktu_acara']; ?>"
    required>

</div>

</div>

<div class="grid">

<div>

<label>Tanggal Acara</label>

<input
    type="date"
    name="tanggal_acara"
    value="<?= $booking['tanggal_acara']; ?>"
    required>

</div>

<div>

<label>Tanggal Pemasangan</label>

<input
    type="date"
    name="tanggal_pemasangan"
    value="<?= $booking['tanggal_pemasangan']; ?>"
    required>

</div>

</div>

<div class="full">

<label>Lokasi Acara</label>

<input
    type="text"
    name="lokasi"
    value="<?= htmlspecialchars($booking['lokasi']); ?>"
    required>

</div>

<div class="full">

<label>Catatan</label>

<textarea
    name="catatan"
    placeholder="Tambahkan catatan jika diperlukan"><?= htmlspecialchars($booking['catatan']); ?></textarea>

</div>

<h3>Alat yang Disewa</h3>

<?php while($barang = mysqli_fetch_assoc($queryInventaris)) :

$value = isset($selected[$barang['id_inventaris']])
    ? $selected[$barang['id_inventaris']]
    : 0;

?>

<div class="inventory-card <?= $value > 0 ? 'active' : ''; ?>">

<div class="inventory-content">

<div class="inventory-left">

<h4><?= htmlspecialchars($barang['nama_barang']); ?></h4>

<p>

Tersedia :
<strong><?= $barang['stok']; ?></strong> unit

</p>

</div>

<div class="inventory-right">

<button
    type="button"
    class="minus"
    <?= $value == 0 ? "disabled" : ""; ?>>
    −
</button>

<span class="jumlah"><?= $value; ?></span>

<button
    type="button"
    class="plus"
    data-max="<?= $barang['stok'] + $value; ?>">
    +
</button>

<input
    type="hidden"
    class="qty-input"
    name="inventaris[<?= $barang['id_inventaris']; ?>]"
    value="<?= $value; ?>">

</div>

</div>

</div>

<?php endwhile; ?>

<div class="action">

<a href="dashboard.php" class="cancel">

Batal

</a>

<button
    type="submit"
    class="btn btn-primary">

Simpan Perubahan

</button>

</div>

</form>

</div>

</div>

<script src="../assets/js/tambah_booking.js"></script>

</body>

</html>