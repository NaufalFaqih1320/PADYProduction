<?php

require_once "../config/database.php";
require_once "../config/auth.php";

adminOnly();

$queryInventaris = mysqli_query($conn,"
SELECT *
FROM inventaris
ORDER BY nama_barang ASC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Inventaris</title>

<link rel="stylesheet"
href="../assets/css/dashboard.css">

</head>

<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <div class="content-box">

            <h3>Daftar Inventaris</h3>

            <div class="tab-content" style="display:block;">

                <?php include "../includes/components/modal_inventaris.php"; ?>

                <?php include "../includes/components/inventaris_card.php"; ?>

            </div>

        </div>

    </div>

</div>

<script src="../assets/js/dashboard.js"></script>

</body>

</html>