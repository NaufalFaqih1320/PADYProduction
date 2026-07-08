<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

$currentTab = 'booking';

$queryBooking = mysqli_query($conn, "
    SELECT *
    FROM booking
    ORDER BY created_at DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booking</title>
    <link rel="stylesheet" href="../assets/css/crew.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include "../includes/layout/topbar.php"; ?>

<?php include "../includes/components/owner_tabs.php"; ?>

<div class="crew-content">

    <div class="crew-page-header">
        <h2>Daftar Booking</h2>
    </div>

    <?php include "../includes/components/modal_booking.php"; ?>
    <?php include "../includes/components/toolbar.php"; ?>
    <?php include "../includes/components/booking_card.php"; ?>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="../assets/js/dashboard.js"></script>

</body>
</html>