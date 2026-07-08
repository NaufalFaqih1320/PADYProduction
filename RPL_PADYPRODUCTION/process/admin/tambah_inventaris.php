<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminOnly();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "admin/inventaris.php");
    exit;
}

$nama_barang = trim($_POST['nama_barang']);
$id_kategori = !empty($_POST['id_kategori']) ? (int) $_POST['id_kategori'] : null;
$stok        = (int) $_POST['stok'];
$kondisi     = $_POST['kondisi'];
$lokasi      = trim($_POST['lokasi']);
$keterangan  = trim($_POST['keterangan']);

if (empty($nama_barang) || empty($lokasi) || $stok < 0) {
    header("Location: " . BASE_URL . "admin/inventaris.php?error=" . urlencode("Lengkapi semua data."));
    exit;
}

$nama_barang_esc = mysqli_real_escape_string($conn, $nama_barang);
$kondisi_esc     = mysqli_real_escape_string($conn, $kondisi);
$lokasi_esc      = mysqli_real_escape_string($conn, $lokasi);
$keterangan_esc  = mysqli_real_escape_string($conn, $keterangan);
$id_kategori_sql = $id_kategori !== null ? "'$id_kategori'" : "NULL";

$query = "
    INSERT INTO inventaris
    (nama_barang, id_kategori, stok, kondisi, lokasi, keterangan)
    VALUES
    ('$nama_barang_esc', $id_kategori_sql, '$stok', '$kondisi_esc', '$lokasi_esc', '$keterangan_esc')
";

if (mysqli_query($conn, $query)) {
    header("Location: " . BASE_URL . "admin/inventaris.php?success=" . urlencode("Barang berhasil ditambahkan."));
} else {
    header("Location: " . BASE_URL . "admin/inventaris.php?error=" . urlencode("Gagal menyimpan data."));
}

exit;
