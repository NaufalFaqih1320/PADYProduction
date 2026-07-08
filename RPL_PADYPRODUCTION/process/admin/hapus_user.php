<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminOnly();

if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL . "admin/users.php");
    exit;
}

$id_user = (int) $_GET['id'];

if ($id_user === (int) $_SESSION['id_user']) {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Tidak bisa menghapus akun sendiri."));
    exit;
}

$cek = mysqli_query($conn, "SELECT id_user FROM users WHERE id_user='$id_user'");

if (mysqli_num_rows($cek) === 0) {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Pengguna tidak ditemukan."));
    exit;
}

if (mysqli_query($conn, "DELETE FROM users WHERE id_user='$id_user'")) {
    header("Location: " . BASE_URL . "admin/users.php?success=" . urlencode("Pengguna berhasil dihapus."));
} else {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Gagal menghapus pengguna. Pengguna mungkin masih memiliki data booking/chat terkait."));
}

exit;
