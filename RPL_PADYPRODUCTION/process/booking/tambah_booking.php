<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/auth.php";

ownerAdmin();

/* ==========================================
   CEK REQUEST
========================================== */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: " . BASE_URL . "owner/dashboard.php");
    exit;
}

/* ==========================================
   AMBIL DATA FORM
========================================== */

$nama_client          = trim($_POST['nama_client']);
$email_client         = trim($_POST['email_client']);
$no_hp                = trim($_POST['no_hp']);
$nama_acara           = trim($_POST['nama_acara']);
$lokasi               = trim($_POST['lokasi']);
$tanggal_acara        = $_POST['tanggal_acara'];
$tanggal_pemasangan   = $_POST['tanggal_pemasangan'];
$waktu_acara          = $_POST['waktu_acara'];
$catatan              = trim($_POST['catatan']);

$id_client = NULL;
$total_harga = 0;

/* ==========================================
   VALIDASI
========================================== */

if (
    empty($nama_client) ||
    empty($email_client) ||
    empty($nama_acara) ||
    empty($lokasi) ||
    empty($tanggal_acara) ||
    empty($tanggal_pemasangan) ||
    empty($waktu_acara)
) {

    header("Location: " . BASE_URL . "owner/tambah_booking.php?error=Lengkapi semua data.");
    exit;
}

if (!isset($_POST['inventaris'])) {

    header("Location: " . BASE_URL . "owner/tambah_booking.php?error=Pilih minimal satu alat.");
    exit;
}

/* ==========================================
   MULAI TRANSAKSI
========================================== */

mysqli_begin_transaction($conn);

try {

    /* ======================================
       ESCAPE DATA
    ====================================== */

    $nama_client = mysqli_real_escape_string($conn, $nama_client);
    $email_client = mysqli_real_escape_string($conn, $email_client);
    $no_hp = mysqli_real_escape_string($conn, $no_hp);
    $nama_acara = mysqli_real_escape_string($conn, $nama_acara);
    $lokasi = mysqli_real_escape_string($conn, $lokasi);
    $catatan = mysqli_real_escape_string($conn, $catatan);

    /* ======================================
       INSERT BOOKING
    ====================================== */

    $queryBooking = "

    INSERT INTO booking
    (

        id_client,
        nama_client,
        email_client,
        no_hp,
        nama_acara,
        lokasi,
        tanggal_acara,
        tanggal_pemasangan,
        waktu_acara,
        total_harga,
        catatan,
        status

    )

    VALUES

    (

        NULL,
        '$nama_client',
        '$email_client',
        '$no_hp',
        '$nama_acara',
        '$lokasi',
        '$tanggal_acara',
        '$tanggal_pemasangan',
        '$waktu_acara',
        '$total_harga',
        '$catatan',
        'Pending'

    )

    ";

    if (!mysqli_query($conn, $queryBooking)) {

        throw new Exception("Gagal menyimpan booking.");

    }

    $id_booking = mysqli_insert_id($conn);

    /* ======================================
       SIMPAN DETAIL BOOKING
    ====================================== */

    $adaBarang = false;

    foreach ($_POST['inventaris'] as $id_inventaris => $jumlah) {

        $jumlah = (int)$jumlah;

        if ($jumlah <= 0) {
            continue;
        }

        $adaBarang = true;

        /* ==============================
           CEK STOK
        ============================== */

        $cekBarang = mysqli_query($conn, "

            SELECT stok

            FROM inventaris

            WHERE id_inventaris = '$id_inventaris'

            FOR UPDATE

        ");

        if (!$cekBarang || mysqli_num_rows($cekBarang) == 0) {

            throw new Exception("Barang tidak ditemukan.");

        }

        $barang = mysqli_fetch_assoc($cekBarang);

        if ($barang['stok'] < $jumlah) {

            throw new Exception("Stok tidak mencukupi.");

        }

        /* ==============================
           INSERT BOOKING DETAIL
        ============================== */

        $insertDetail = "

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

        ";

        if (!mysqli_query($conn, $insertDetail)) {

            throw new Exception("Gagal menyimpan detail booking.");

        }

        /* ==============================
           KURANGI STOK
        ============================== */

        $updateStok = "

        UPDATE inventaris

        SET stok = stok - $jumlah

        WHERE id_inventaris = '$id_inventaris'

        ";

        if (!mysqli_query($conn, $updateStok)) {

            throw new Exception("Gagal mengurangi stok.");

        }
    }

    if (!$adaBarang) {

        throw new Exception("Pilih minimal satu alat.");

    }

    /* ======================================
       COMMIT
    ====================================== */

    mysqli_commit($conn);

    header("Location: " . BASE_URL . "owner/dashboard.php?success=Booking berhasil ditambahkan");

} catch (Exception $e) {

    mysqli_rollback($conn);

    header("Location: " . BASE_URL . "owner/tambah_booking.php?error=" . urlencode($e->getMessage()));
}

exit;

?>