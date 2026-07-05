<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/session.php";

$nama       = htmlspecialchars($_POST['nama']);
$email      = htmlspecialchars($_POST['email']);
$no_hp      = htmlspecialchars($_POST['no_hp']);
$password   = $_POST['password'];
$konfirmasi = $_POST['konfirmasi'];

/* ===============================
   VALIDASI PASSWORD
================================ */

if($password != $konfirmasi){

    header("Location: " . BASE_URL . "register.php?error=Password tidak sama");
    exit;

}

/* ===============================
   CEK EMAIL
================================ */

$cek = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($cek)>0){

    header("Location: " . BASE_URL . "register.php?error=Email sudah digunakan");
    exit;

}

/* ===============================
   HASH PASSWORD
================================ */

$passwordHash = password_hash($password,PASSWORD_DEFAULT);

/* ===============================
   INSERT DATABASE
================================ */

$query = mysqli_query($conn,"INSERT INTO users
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
'$passwordHash',
'$no_hp',
'client',
'aktif'
)");

if($query){

    header("Location: " . BASE_URL . "login.php?success=Registrasi berhasil");

}else{

    header("Location: " . BASE_URL . "register.php?error=Registrasi gagal");

}

?>