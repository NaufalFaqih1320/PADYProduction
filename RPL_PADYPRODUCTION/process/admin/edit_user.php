<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminOnly();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "admin/users.php");
    exit;
}

$id_user  = (int) $_POST['id_user'];
$nama     = trim($_POST['nama']);
$email    = trim($_POST['email']);
$no_hp    = trim($_POST['no_hp']);
$password = $_POST['password'];
$role     = $_POST['role'];
$status   = $_POST['status'];

$roleValid   = ['owner', 'admin', 'crew', 'client'];
$statusValid = ['aktif', 'nonaktif'];

if (
    empty($id_user) ||
    empty($nama) ||
    empty($email) ||
    !in_array($role, $roleValid) ||
    !in_array($status, $statusValid)
) {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Lengkapi semua data dengan benar."));
    exit;
}

if (!empty($password) && strlen($password) < 6) {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Password minimal 6 karakter."));
    exit;
}

$cekEmail = mysqli_query($conn, "
    SELECT id_user FROM users
    WHERE email='" . mysqli_real_escape_string($conn, $email) . "'
      AND id_user != '$id_user'
");

if (mysqli_num_rows($cekEmail) > 0) {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Email sudah digunakan pengguna lain."));
    exit;
}

$namaEsc  = mysqli_real_escape_string($conn, $nama);
$emailEsc = mysqli_real_escape_string($conn, $email);
$noHpEsc  = mysqli_real_escape_string($conn, $no_hp);

if (!empty($password)) {

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $query = "
        UPDATE users
        SET nama='$namaEsc', email='$emailEsc', no_hp='$noHpEsc',
            password='$passwordHash', role='$role', status='$status'
        WHERE id_user='$id_user'
    ";

} else {

    $query = "
        UPDATE users
        SET nama='$namaEsc', email='$emailEsc', no_hp='$noHpEsc',
            role='$role', status='$status'
        WHERE id_user='$id_user'
    ";

}

if (mysqli_query($conn, $query)) {
    header("Location: " . BASE_URL . "admin/users.php?success=" . urlencode("Data pengguna berhasil diperbarui."));
} else {
    header("Location: " . BASE_URL . "admin/users.php?error=" . urlencode("Gagal memperbarui data pengguna."));
}

exit;
