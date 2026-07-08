<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminOnly();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "admin/users.php");
    exit;
}

$nama     = trim($_POST['nama']);
$email    = trim($_POST['email']);
$no_hp    = trim($_POST['no_hp']);
$password = $_POST['password'];
$role     = $_POST['role'];
$status   = $_POST['status'];

$roleValid   = ['owner', 'admin', 'crew', 'client'];
$statusValid = ['aktif', 'nonaktif'];

if (
    empty($nama) ||
    empty($email) ||
    empty($password) ||
    strlen($password) < 6 ||
    !in_array($role, $roleValid) ||
    !in_array($status, $statusValid)
) {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Lengkapi semua data dengan benar."));
    exit;
}

$cekEmail = mysqli_query($conn, "SELECT id_user FROM users WHERE email='" . mysqli_real_escape_string($conn, $email) . "'");

if (mysqli_num_rows($cekEmail) > 0) {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Email sudah digunakan."));
    exit;
}

$namaEsc     = mysqli_real_escape_string($conn, $nama);
$emailEsc    = mysqli_real_escape_string($conn, $email);
$noHpEsc     = mysqli_real_escape_string($conn, $no_hp);
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$query = "
    INSERT INTO users
    (nama, email, password, no_hp, role, status)
    VALUES
    ('$namaEsc', '$emailEsc', '$passwordHash', '$noHpEsc', '$role', '$status')
";

if (mysqli_query($conn, $query)) {
    header("Location: " . BASE_URL . "admin/users.php?success=" . urlencode("Pengguna berhasil ditambahkan."));
} else {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Gagal menambahkan pengguna."));
}

exit;