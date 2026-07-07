<?php

$role = $_SESSION['role'];

?>

<div class="stat-card-wrapper">

<?php if($role == "owner") : ?>

    <div class="stat-card">
        <h3><?= $totalBooking; ?></h3>
        <p>Total Booking</p>
    </div>

    <div class="stat-card">
        <h3><?= $bookingHariIni; ?></h3>
        <p>Booking Hari Ini</p>
    </div>

    <div class="stat-card">
        <h3><?= $totalInventaris; ?></h3>
        <p>Total Inventaris</p>
    </div>

    <div class="stat-card">
        <h3><?= $totalChat; ?></h3>
        <p>Chat Baru</p>
    </div>

<?php elseif($role == "admin") : ?>

    <div class="stat-card">
        <h3><?= $totalUser; ?></h3>
        <p>Total Pengguna</p>
    </div>

    <div class="stat-card">
        <h3><?= $totalBooking; ?></h3>
        <p>Total Booking</p>
    </div>

    <div class="stat-card">
        <h3><?= $bookingHariIni; ?></h3>
        <p>Booking Hari Ini</p>
    </div>

    <div class="stat-card">
        <h3><?= $totalInventaris; ?></h3>
        <p>Total Inventaris</p>
    </div>

<?php endif; ?>

</div>