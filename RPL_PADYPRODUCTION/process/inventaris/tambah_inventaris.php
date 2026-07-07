<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminCrew();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "crew/inventaris.php");
    exit;
}

$nama_barang = trim($_POST['nama_barang']);
$id_kategori = (int) $_POST['id_kategori'];
$stok        = (int) $_POST['stok'];
$kondisi     = $_POST['kondisi'];
$lokasi      = trim($_POST['lokasi']);
$keterangan  = trim($_POST['keterangan']);

if (empty($nama_barang) || empty($lokasi) || $stok < 0) {
    header("Location: " . BASE_URL . "crew/inventaris.php?error=Lengkapi semua data.");
    exit;
}

$nama_barang_esc = mysqli_real_escape_string($conn, $nama_barang);
$kondisi_esc     = mysqli_real_escape_string($conn, $kondisi);
$lokasi_esc      = mysqli_real_escape_string($conn, $lokasi);
$keterangan_esc  = mysqli_real_escape_string($conn, $keterangan);

$query = "
    INSERT INTO inventaris
    (nama_barang, id_kategori, stok, kondisi, lokasi, keterangan)
    VALUES
    ('$nama_barang_esc', '$id_kategori', '$stok', '$kondisi_esc', '$lokasi_esc', '$keterangan_esc')
";

if (mysqli_query($conn, $query)) {
    header("Location: " . BASE_URL . "crew/inventaris.php?success=Barang berhasil ditambahkan");
} else {
    header("Location: " . BASE_URL . "crew/inventaris.php?error=Gagal menyimpan data.");
}

exit;