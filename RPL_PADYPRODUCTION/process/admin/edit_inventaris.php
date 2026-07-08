<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminOnly();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "admin/inventaris.php");
    exit;
}

$id_inventaris = (int) $_POST['id_inventaris'];
$nama_barang   = trim($_POST['nama_barang']);
$id_kategori   = !empty($_POST['id_kategori']) ? (int) $_POST['id_kategori'] : null;
$stok          = (int) $_POST['stok'];
$kondisi       = $_POST['kondisi'];
$lokasi        = trim($_POST['lokasi']);
$keterangan    = trim($_POST['keterangan']);

if (empty($id_inventaris) || empty($nama_barang) || empty($lokasi) || $stok < 0) {
    header("Location: " . BASE_URL . "admin/inventaris.php?error=" . urlencode("Lengkapi semua data."));
    exit;
}

$nama_barang_esc = mysqli_real_escape_string($conn, $nama_barang);
$kondisi_esc     = mysqli_real_escape_string($conn, $kondisi);
$lokasi_esc      = mysqli_real_escape_string($conn, $lokasi);
$keterangan_esc  = mysqli_real_escape_string($conn, $keterangan);
$id_kategori_sql = $id_kategori !== null ? "'$id_kategori'" : "NULL";

$query = "
    UPDATE inventaris
    SET nama_barang='$nama_barang_esc',
        id_kategori=$id_kategori_sql,
        stok='$stok',
        kondisi='$kondisi_esc',
        lokasi='$lokasi_esc',
        keterangan='$keterangan_esc'
    WHERE id_inventaris='$id_inventaris'
";

if (mysqli_query($conn, $query)) {
    header("Location: " . BASE_URL . "admin/inventaris.php?success=" . urlencode("Data inventaris berhasil diperbarui."));
} else {
    header("Location: " . BASE_URL . "admin/inventaris.php?error=" . urlencode("Gagal memperbarui data."));
}

exit;
