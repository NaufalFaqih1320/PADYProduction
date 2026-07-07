<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminOnly();

/*==========================================
    AMBIL DATA
==========================================*/

$id       = (int) $_POST['id_user'];
$nama     = trim($_POST['nama']);
$email    = trim($_POST['email']);
$password = trim($_POST['password']);
$no_hp    = trim($_POST['no_hp']);
$role     = trim($_POST['role']);
$status   = trim($_POST['status']);

/*==========================================
    VALIDASI
==========================================*/

if(
    empty($nama) ||
    empty($email) ||
    empty($no_hp) ||
    empty($role) ||
    empty($status)
){

    echo "<script>
            alert('Semua data wajib diisi!');
            window.history.back();
          </script>";

    exit;

}

/*==========================================
    CEK EMAIL DUPLIKAT
==========================================*/

$cek = mysqli_query($conn,"
    SELECT *
    FROM user
    WHERE email='$email'
    AND id_user != '$id'
");

if(mysqli_num_rows($cek) > 0){

    echo "<script>
            alert('Email sudah digunakan!');
            window.history.back();
          </script>";

    exit;

}

/*==========================================
    UPDATE TANPA PASSWORD
==========================================*/

if(empty($password)){

    $query = mysqli_query($conn,"
        UPDATE user
        SET
            nama='$nama',
            email='$email',
            no_hp='$no_hp',
            role='$role',
            status='$status'
        WHERE id_user='$id'
    ");

}

/*==========================================
    UPDATE DENGAN PASSWORD
==========================================*/

else{

    $password = password_hash($password, PASSWORD_DEFAULT);

    $query = mysqli_query($conn,"
        UPDATE user
        SET
            nama='$nama',
            email='$email',
            password='$password',
            no_hp='$no_hp',
            role='$role',
            status='$status'
        WHERE id_user='$id'
    ");

}

/*==========================================
    HASIL
==========================================*/

if($query){

    echo "<script>

        alert('Data pengguna berhasil diperbarui!');

        window.location='../../admin/pengguna.php';

    </script>";

}else{

    echo "<script>

        alert('Gagal memperbarui data!');

        window.history.back();

    </script>";

}