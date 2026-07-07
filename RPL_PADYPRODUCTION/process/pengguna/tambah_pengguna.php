<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminOnly();

/*==========================================
    AMBIL DATA FORM
==========================================*/

$nama     = trim($_POST['nama']);
$email    = trim($_POST['email']);
$password = $_POST['password'];
$no_hp    = trim($_POST['no_hp']);
$role     = $_POST['role'];
$status   = $_POST['status'];

/*==========================================
    VALIDASI
==========================================*/

if(
    empty($nama) ||
    empty($email) ||
    empty($password) ||
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
    CEK EMAIL
==========================================*/

$cek = mysqli_query($conn,"
    SELECT *
    FROM user
    WHERE email='$email'
");

if(mysqli_num_rows($cek) > 0){

    echo "<script>
            alert('Email sudah digunakan!');
            window.history.back();
          </script>";

    exit;
}

/*==========================================
    HASH PASSWORD
==========================================*/

$password = password_hash($password, PASSWORD_DEFAULT);

/*==========================================
    SIMPAN DATA
==========================================*/

$query = mysqli_query($conn,"
INSERT INTO user
(
    nama,
    email,
    password,
    no_hp,
    role,
    status
)
VALUES
(
    '$nama',
    '$email',
    '$password',
    '$no_hp',
    '$role',
    '$status'
)
");

/*==========================================
    HASIL
==========================================*/

if($query){

    echo "<script>

        alert('Pengguna berhasil ditambahkan!');

        window.location='../../admin/pengguna.php';

    </script>";

}else{

    echo "<script>

        alert('Gagal menambahkan pengguna!');

        window.history.back();

    </script>";

}