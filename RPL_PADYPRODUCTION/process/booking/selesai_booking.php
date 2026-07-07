<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

ownerAdmin();

/*==========================================
    CEK ID
==========================================*/

if (!isset($_GET['id'])) {

    header("Location: " . BASE_URL . "owner/dashboard.php");
    exit;

}

$id_booking = (int) $_GET['id'];

mysqli_begin_transaction($conn);

try {

    /*==========================================
        CEK BOOKING
    ==========================================*/

    $cekBooking = mysqli_query($conn, "
        SELECT *
        FROM booking
        WHERE id_booking='$id_booking'
        FOR UPDATE
    ");

    if (mysqli_num_rows($cekBooking) == 0) {

        throw new Exception("Booking tidak ditemukan.");

    }

    $booking = mysqli_fetch_assoc($cekBooking);

    if ($booking['status'] == "Selesai") {

        throw new Exception("Booking sudah diselesaikan.");

    }

    /*==========================================
        AMBIL DETAIL BOOKING
    ==========================================*/

    $detail = mysqli_query($conn, "
        SELECT *
        FROM booking_detail
        WHERE id_booking='$id_booking'
    ");

    while ($row = mysqli_fetch_assoc($detail)) {

        mysqli_query($conn, "

            UPDATE inventaris

            SET stok = stok + {$row['jumlah']}

            WHERE id_inventaris='{$row['id_inventaris']}'

        ");

    }

    /*==========================================
        UPDATE STATUS
    ==========================================*/

    mysqli_query($conn, "

        UPDATE booking

        SET status='Selesai'

        WHERE id_booking='$id_booking'

    ");

    mysqli_commit($conn);

    header("Location: " . BASE_URL . "owner/dashboard.php?success=selesai");

} catch (Exception $e) {

    mysqli_rollback($conn);

    header("Location: " . BASE_URL . "owner/dashboard.php?error=" . urlencode($e->getMessage()));

}

exit;

?>