<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

ownerOnly();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    header("Location: " . BASE_URL . "owner/dashboard.php");
    exit;

}

mysqli_begin_transaction($conn);

try {

    /*==========================================
        AMBIL DATA FORM
    ==========================================*/

    $id_booking        = (int) $_POST['id_booking'];
    $nama_client       = mysqli_real_escape_string($conn, $_POST['nama_client']);
    $email_client      = mysqli_real_escape_string($conn, $_POST['email_client']);
    $no_hp             = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $nama_acara        = mysqli_real_escape_string($conn, $_POST['nama_acara']);
    $lokasi            = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $tanggal_acara     = $_POST['tanggal_acara'];
    $tanggal_pemasangan = $_POST['tanggal_pemasangan'];
    $waktu_acara       = $_POST['waktu_acara'];
    $catatan           = mysqli_real_escape_string($conn, $_POST['catatan']);

    /*==========================================
        KEMBALIKAN STOK LAMA
    ==========================================*/

    $detailLama = mysqli_query($conn, "
        SELECT *
        FROM booking_detail
        WHERE id_booking='$id_booking'
    ");

    while ($row = mysqli_fetch_assoc($detailLama)) {

        mysqli_query($conn, "
            UPDATE inventaris

            SET stok = stok + {$row['jumlah']}

            WHERE id_inventaris = '{$row['id_inventaris']}'
        ");

    }

    /*==========================================
        HAPUS DETAIL LAMA
    ==========================================*/

    mysqli_query($conn, "
        DELETE FROM booking_detail
        WHERE id_booking='$id_booking'
    ");

    /*==========================================
        UPDATE BOOKING
    ==========================================*/

    mysqli_query($conn, "

        UPDATE booking

        SET

            nama_client='$nama_client',
            email_client='$email_client',
            no_hp='$no_hp',
            nama_acara='$nama_acara',
            lokasi='$lokasi',
            tanggal_acara='$tanggal_acara',
            tanggal_pemasangan='$tanggal_pemasangan',
            waktu_acara='$waktu_acara',
            catatan='$catatan'

        WHERE id_booking='$id_booking'

    ");

    /*==========================================
        SIMPAN DETAIL BARU
    ==========================================*/

    if (isset($_POST['inventaris'])) {

        foreach ($_POST['inventaris'] as $id_inventaris => $jumlah) {

            $jumlah = (int) $jumlah;

            if ($jumlah <= 0) {
                continue;
            }

            /*==========================================
                CEK STOK
            ==========================================*/

            $cek = mysqli_query($conn, "

                SELECT stok

                FROM inventaris

                WHERE id_inventaris='$id_inventaris'

                FOR UPDATE

            ");

            $barang = mysqli_fetch_assoc($cek);

            if (!$barang) {

                throw new Exception("Inventaris tidak ditemukan.");

            }

            if ($barang['stok'] < $jumlah) {

                throw new Exception("Stok {$id_inventaris} tidak mencukupi.");

            }

            /*==========================================
                INSERT DETAIL BARU
            ==========================================*/

            mysqli_query($conn, "

                INSERT INTO booking_detail
                (
                    id_booking,
                    id_inventaris,
                    jumlah
                )

                VALUES
                (
                    '$id_booking',
                    '$id_inventaris',
                    '$jumlah'
                )

            ");

            /*==========================================
                KURANGI STOK
            ==========================================*/

            mysqli_query($conn, "

                UPDATE inventaris

                SET stok = stok - $jumlah

                WHERE id_inventaris='$id_inventaris'

            ");

        }

    }

    mysqli_commit($conn);

    header("Location: " . BASE_URL . "owner/dashboard.php?success=edit");

} catch (Exception $e) {

    mysqli_rollback($conn);

    header("Location: " . BASE_URL . "owner/edit_booking.php?id=" . $id_booking . "&error=" . urlencode($e->getMessage()));

}

exit;

?>