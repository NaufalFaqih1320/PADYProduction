<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

adminOnly();

/*==========================================
    CEK ID
==========================================*/

if(!isset($_GET['id'])){

    header("Location: ../../admin/pengguna.php");
    exit;

}

$id = (int) $_GET['id'];

/*==========================================
    CEK DATA USER
==========================================*/

$query = mysqli_query($conn,"
    SELECT *
    FROM user
    WHERE id_user='$id'
");

if(mysqli_num_rows($query) == 0){

    header("Location: ../../admin/pengguna.php");
    exit;

}

$user = mysqli_fetch_assoc($query);

/*==========================================
    TIDAK BOLEH HAPUS OWNER
==========================================*/

if($user['role'] == "owner"){

    echo "<script>

        alert('Akun Owner tidak dapat dihapus!');

        window.location='../../admin/pengguna.php';

    </script>";

    exit;

}

/*==========================================
    TIDAK BOLEH HAPUS DIRI SENDIRI
==========================================*/

if($_SESSION['id_user'] == $id){

    echo "<script>

        alert('Anda tidak dapat menghapus akun sendiri!');

        window.location='../../admin/pengguna.php';

    </script>";

    exit;

}

/*==========================================
    HAPUS DATA
==========================================*/

$hapus = mysqli_query($conn,"
    DELETE FROM user
    WHERE id_user='$id'
");

/*==========================================
    HASIL
==========================================*/

if($hapus){

    echo "<script>

        alert('Pengguna berhasil dihapus!');

        window.location='../../admin/pengguna.php';

    </script>";

}else{

    echo "<script>

        alert('Gagal menghapus pengguna!');

        window.location='../../admin/pengguna.php';

    </script>";

}