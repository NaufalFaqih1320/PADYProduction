<?php
require_once "config/session.php";
alreadyLogin();
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | PADY Production</title>

    <link rel="stylesheet" href="assets/css/register.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="container">

    <div class="left">

        <!-- GANTI GAMBAR REGISTER -->
        <img src="assets/images/register.jpg" alt="Register">

    </div>

    <div class="right">

        <div class="card">

            <a href="index.php" class="back-btn">

                ← Kembali ke Beranda

            </a>

            <h2>Daftar Akun</h2>

            <p>Silakan buat akun untuk mulai menggunakan layanan PADY Production.</p>

            <?php
            if(isset($_GET['error'])){
                echo "<div class='error'>".$_GET['error']."</div>";
            }
            ?>

            <form action="process/auth/register_process.php" method="POST">

                <input
                    type="text"
                    name="nama"
                    placeholder="Nama Lengkap"
                    required
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required
                >

                <input
                    type="text"
                    name="no_hp"
                    placeholder="Nomor HP"
                    required
                >

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Password"
                    required
                >

                <input
                    type="password"
                    name="konfirmasi"
                    id="konfirmasi"
                    placeholder="Konfirmasi Password"
                    required
                >

                <button type="submit">

                    Daftar

                </button>

            </form>

            <div class="login">

                Sudah punya akun?

                <a href="login.php">

                    Login

                </a>

            </div>

        </div>

    </div>

</div>

<script src="assets/js/register.js"></script>

</body>
</html>