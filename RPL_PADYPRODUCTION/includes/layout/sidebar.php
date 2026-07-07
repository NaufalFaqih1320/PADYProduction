<?php

$role = $_SESSION['role'] ?? '';

?>

<div class="sidebar">

    <div class="logo">

        <img src="../assets/images/logo.png" alt="PADY">

    </div>

    <ul class="menu">

        <?php if($role == "owner"): ?>

            <li><a href="../owner/dashboard.php">Dashboard</a></li>
            <li><a href="../owner/booking.php">Daftar Booking</a></li>
            <li><a href="../owner/jadwal.php">Jadwal</a></li>
            <li><a href="../owner/inventaris.php">Informasi Inventaris</a></li>
            <li><a href="../owner/chat.php">Chat</a></li>
            <li><a href="../owner/reminder.php">Reminder</a></li>
            <li><a href="../owner/laporan.php">Laporan</a></li>

        <?php elseif($role == "admin"): ?>

            <li><a href="../admin/dashboard.php">Dashboard</a></li>
            <li><a href="../admin/pengguna.php">Manajemen Pengguna</a></li>
            <li><a href="../admin/booking.php">Daftar Booking</a></li>
            <li><a href="../admin/inventaris.php">Inventaris</a></li>

        <?php endif; ?>

    </ul>

    <div class="logout">

        <a href="../logout.php">Keluar</a>

    </div>

</div>