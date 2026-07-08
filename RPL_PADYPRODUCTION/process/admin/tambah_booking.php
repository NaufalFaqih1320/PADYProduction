<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/auth.php";

adminOnly();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "admin/dashboard.php");
    exit;
}

$nama_client        = trim($_POST['nama_client']);
$email_client       = trim($_POST['email_client']);
$no_hp              = trim($_POST['no_hp']);
$nama_acara         = trim($_POST['nama_acara']);
$lokasi             = trim($_POST['lokasi']);
$tanggal_acara      = $_POST['tanggal_acara'];
$tanggal_pemasangan = $_POST['tanggal_pemasangan'];
$waktu_acara        = $_POST['waktu_acara'];
$catatan            = trim($_POST['catatan']);

$total_harga = 0;

if (
    empty($nama_client) ||
    empty($email_client) ||
    empty($nama_acara) ||
    empty($lokasi) ||
    empty($tanggal_acara) ||
    empty($tanggal_pemasangan) ||
    empty($waktu_acara)
) {
    header("Location: " . BASE_URL . "admin/tambah_booking.php?error=" . urlencode("Lengkapi semua data."));
    exit;
}

if (!isset($_POST['inventaris'])) {
    header("Location: " . BASE_URL . "admin/tambah_booking.php?error=" . urlencode("Pilih minimal satu alat."));
    exit;
}

mysqli_begin_transaction($conn);

try {

    $nama_client  = mysqli_real_escape_string($conn, $nama_client);
    $email_client = mysqli_real_escape_string($conn, $email_client);
    $no_hp        = mysqli_real_escape_string($conn, $no_hp);
    $nama_acara   = mysqli_real_escape_string($conn, $nama_acara);
    $lokasi       = mysqli_real_escape_string($conn, $lokasi);
    $catatan      = mysqli_real_escape_string($conn, $catatan);

    $queryBooking = "
        INSERT INTO booking
        (id_client, nama_client, email_client, no_hp, nama_acara, lokasi,
         tanggal_acara, tanggal_pemasangan, waktu_acara, total_harga, catatan, status)
        VALUES
        (NULL, '$nama_client', '$email_client', '$no_hp', '$nama_acara', '$lokasi',
         '$tanggal_acara', '$tanggal_pemasangan', '$waktu_acara', '$total_harga', '$catatan', 'Pending')
    ";

    if (!mysqli_query($conn, $queryBooking)) {
        throw new Exception("Gagal menyimpan booking.");
    }

    $id_booking = mysqli_insert_id($conn);
    $adaBarang = false;

    foreach ($_POST['inventaris'] as $id_inventaris => $jumlah) {

        $jumlah = (int) $jumlah;

        if ($jumlah <= 0) {
            continue;
        }

        $adaBarang = true;

        $cekBarang = mysqli_query($conn, "
            SELECT stok FROM inventaris WHERE id_inventaris = '$id_inventaris' FOR UPDATE
        ");

        if (!$cekBarang || mysqli_num_rows($cekBarang) == 0) {
            throw new Exception("Barang tidak ditemukan.");
        }

        $barang = mysqli_fetch_assoc($cekBarang);

        if ($barang['stok'] < $jumlah) {
            throw new Exception("Stok tidak mencukupi.");
        }

        if (!mysqli_query($conn, "
            INSERT INTO booking_detail (id_booking, id_inventaris, jumlah)
            VALUES ('$id_booking', '$id_inventaris', '$jumlah')
        ")) {
            throw new Exception("Gagal menyimpan detail booking.");
        }

        if (!mysqli_query($conn, "
            UPDATE inventaris SET stok = stok - $jumlah WHERE id_inventaris='$id_inventaris'
        ")) {
            throw new Exception("Gagal mengurangi stok.");
        }
    }

    if (!$adaBarang) {
        throw new Exception("Pilih minimal satu alat.");
    }

    mysqli_commit($conn);

    header("Location: " . BASE_URL . "admin/booking.php?success=" . urlencode("Booking berhasil ditambahkan."));

} catch (Exception $e) {

    mysqli_rollback($conn);
    header("Location: " . BASE_URL . "admin/tambah_booking.php?error=" . urlencode($e->getMessage()));
}

exit;
