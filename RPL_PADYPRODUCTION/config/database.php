<?php

/*==========================================
    KONFIGURASI DATABASE
==========================================*/

$host = "localhost";
$user = "root";
$pass = "";
$db   = "pady";

/*==========================================
    KONEKSI DATABASE
==========================================*/

$conn = mysqli_connect($host, $user, $pass, $db);

/*==========================================
    CEK KONEKSI
==========================================*/

if (!$conn) {

    die("Koneksi database gagal : " . mysqli_connect_error());

}

/*==========================================
    SET CHARSET UTF8
==========================================*/

mysqli_set_charset($conn, "utf8");

?>