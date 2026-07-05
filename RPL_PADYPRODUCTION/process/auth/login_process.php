<?php

require_once "../../config/database.php";
require_once "../../config/session.php";

/*==========================================
    AMBIL DATA DARI FORM
==========================================*/

$email    = htmlspecialchars(trim($_POST['email']));
$password = $_POST['password'];

/*==========================================
    VALIDASI INPUT
==========================================*/

if (empty($email) || empty($password)) {

    header("Location: " . BASE_URL . "login.php?error=Email dan Password wajib diisi");
    exit;

}

/*==========================================
    CEK EMAIL
==========================================*/

$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if (mysqli_num_rows($query) == 0) {

    header("Location: " . BASE_URL . "login.php?error=Email tidak terdaftar");
    exit;

}

$user = mysqli_fetch_assoc($query);

/*==========================================
    CEK STATUS AKUN
==========================================*/

if ($user['status'] != 'aktif') {

    header("Location: " . BASE_URL . "login.php?error=Akun tidak aktif");
    exit;

}

/*==========================================
    VERIFIKASI PASSWORD
==========================================*/

if (!password_verify($password, $user['password'])) {

    header("Location: " . BASE_URL . "login.php?error=Password salah");
    exit;

}

/*==========================================
    BUAT SESSION
==========================================*/

$_SESSION['login'] = true;
$_SESSION['id_user'] = $user['id_user'];
$_SESSION['nama'] = $user['nama'];
$_SESSION['email'] = $user['email'];
$_SESSION['role'] = $user['role'];

/*==========================================
    REDIRECT BERDASARKAN ROLE
==========================================*/

switch ($user['role']) {

    case 'owner':
        header("Location: " . BASE_URL . "owner/dashboard.php");
        break;

    case 'admin':
        header("Location: " . BASE_URL . "admin/dashboard.php");
        break;

    case 'crew':
        header("Location: " . BASE_URL . "crew/dashboard.php");
        break;

    case 'client':
        header("Location: " . BASE_URL . "index.php");
        break;

    default:
        header("Location: " . BASE_URL . "login.php?error=Role tidak dikenali");
        break;
}

exit;

?>