<?php

require_once "config/session.php";
alreadyLogin();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | PADY Production</title>

    <link rel="stylesheet" href="assets/css/login.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="container">

    <div class="left">

        <!-- GANTI GAMBAR LOGIN -->
        <img src="assets/images/login.jpg" alt="Login">

    </div>

    <div class="right">

        <div class="card">

            <a href="index.php" class="back-btn">

                ← Kembali ke Beranda

            </a>

            <h2>Selamat Datang</h2>

            <p>Silakan login ke akun Anda.</p>

            <?php
            if(isset($_GET['success'])){
                echo "<div class='success'>".$_GET['success']."</div>";
            }

            if(isset($_GET['error'])){
                echo "<div class='error'>".$_GET['error']."</div>";
            }
            ?>

            <form action="process/auth/login_process.php" method="POST">

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required
                >

                <div class="password-box">

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Password"
                        required
                    >

                    <span id="togglePassword">
                        👁
                    </span>

                </div>

                <button type="submit">

                    Login

                </button>

            </form>

            <div class="register">

                Belum punya akun?

                <a href="register.php">

                    Daftar

                </a>

            </div>

        </div>

    </div>

</div>

<script src="assets/js/login.js"></script>

</body>

</html>