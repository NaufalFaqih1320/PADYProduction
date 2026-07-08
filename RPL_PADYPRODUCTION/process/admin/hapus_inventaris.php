<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminOnly();

if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL . "admin/inventaris.php");
    exit;
}

$id_inventaris = (int) $_GET['id'];

$cek = mysqli_query($conn, "SELECT id_inventaris FROM inventaris WHERE id_inventaris='$id_inventaris'");

if (mysqli_num_rows($cek) === 0) {
    header("Location: " . BASE_URL . "admin/inventaris.php?error=" . urlencode("Barang tidak ditemukan."));
    exit;
}

if (mysqli_query($conn, "DELETE FROM inventaris WHERE id_inventaris='$id_inventaris'")) {
    header("Location: " . BASE_URL . "admin/inventaris.php?success=" . urlencode("Barang berhasil dihapus."));
} else {
    header("Location: " . BASE_URL . "admin/inventaris.php?error=" . urlencode("Gagal menghapus barang. Barang mungkin masih terkait dengan data booking."));
}

exit;
