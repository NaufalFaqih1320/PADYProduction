<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

$queryBooking = mysqli_query($conn, "
    SELECT *
    FROM booking
    ORDER BY created_at DESC
");

/* ===========================
   TOTAL BOOKING
=========================== */

$totalBooking = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM booking
    ")
)['total'];

/* ===========================
   BOOKING HARI INI
=========================== */

$bookingHariIni = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM booking
        WHERE tanggal_acara = CURDATE()
    ")
)['total'];

/* ===========================
   TOTAL INVENTARIS
=========================== */

$totalInventaris = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM inventaris
    ")
)['total'];

/* ===========================
   CHAT BARU
=========================== */

$totalChat = 0;

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard Owner</title>

    <link rel="stylesheet"
          href="../assets/css/dashboard.css">

</head>

<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <?php include "../includes/components/stat_card.php"; ?>

        <div class="content-box">

            <?php include "../includes/components/tabs.php"; ?>

            <div class="tab-content">

                <?php include "../includes/components/modal_booking.php"; ?>

                <?php include "../includes/components/toolbar.php"; ?>

                <?php include "../includes/components/booking_card.php"; ?>


            </div>

            <div class="tab-content" style="display:none;">

                <?php include "../includes/components/modal_booking.php"; ?>

                <h3>Jadwal Booking</h3>

                <p>Data jadwal akan ditampilkan di sini.</p>

            </div>

            <div class="tab-content" style="display:none;">

                <?php include "../includes/components/modal_booking.php"; ?>

                <h3>Informasi Inventaris</h3>

                <p>Data inventaris akan ditampilkan di sini.</p>

            </div>

        </div>

        <script src="../assets/js/dashboard.js"></script>

    </div>

</div>

</body>

</html>