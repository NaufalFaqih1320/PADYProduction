<?php

require_once "../config/database.php";
require_once "../config/auth.php";

adminOnly();

/*==========================================
    TOTAL USER
==========================================*/

$totalUser = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM user
    ")
)['total'];

/*==========================================
    TOTAL BOOKING
==========================================*/

$totalBooking = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM booking
    ")
)['total'];

/*==========================================
    BOOKING HARI INI
==========================================*/

$bookingHariIni = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM booking
        WHERE tanggal_acara = CURDATE()
    ")
)['total'];

/*==========================================
    TOTAL INVENTARIS
==========================================*/

$totalInventaris = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM inventaris
    ")
)['total'];

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin</title>

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <?php include "../includes/components/stat_card.php"; ?>

    </div>

</div>

<script src="../assets/js/dashboard.js"></script>

</body>
</html>