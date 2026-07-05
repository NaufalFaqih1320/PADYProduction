<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/session.php";

checkLogin();

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: " . BASE_URL . "owner/dashboard.php");
    exit;
}

/* ======================
   AMBIL DATA
====================== */

$nama_client = mysqli_real_escape_string($conn,$_POST['nama_client']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$telepon = mysqli_real_escape_string($conn,$_POST['telepon']);
$lokasi = mysqli_real_escape_string($conn,$_POST['lokasi']);
$tanggal_acara = $_POST['tanggal_acara'];

/* ======================
   SIMPAN
====================== */

$query = mysqli_query($conn,"INSERT INTO booking
(
nama_client,
email,
telepon,
lokasi,
tanggal_acara,
status
)

VALUES

(
'$nama_client',
'$email',
'$telepon',
'$lokasi',
'$tanggal_acara',
'Menunggu'
)");

if($query){

    header("Location: ".BASE_URL."owner/dashboard.php?success=1");

}else{

    header("Location: ".BASE_URL."owner/dashboard.php?error=1");

}