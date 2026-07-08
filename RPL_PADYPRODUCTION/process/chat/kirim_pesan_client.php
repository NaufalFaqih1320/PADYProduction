<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";
require_once "../../config/helpers.php";

clientOnly();

header("Content-Type: application/json");

$clientId = (int) $_SESSION['id_user'];
$ownerId  = getPrimaryOwnerId($conn);
$pesan    = trim($_POST['pesan'] ?? '');

if (!$ownerId) {
    echo json_encode([
        "success" => false,
        "message" => "Owner tidak ditemukan.",
    ]);
    exit;
}

if ($pesan === '') {
    echo json_encode([
        "success" => false,
        "message" => "Pesan tidak boleh kosong.",
    ]);
    exit;
}

$pesanEsc = mysqli_real_escape_string($conn, $pesan);

$insert = mysqli_query($conn, "
    INSERT INTO chat_pesan (pengirim_id, penerima_id, pesan)
    VALUES ($clientId, $ownerId, '$pesanEsc')
");

if (!$insert) {
    echo json_encode([
        "success" => false,
        "message" => "Gagal mengirim pesan.",
    ]);
    exit;
}

$idPesan = mysqli_insert_id($conn);

$row = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT id_pesan, pesan, created_at
    FROM chat_pesan
    WHERE id_pesan = $idPesan
"));

echo json_encode([
    "success" => true,
    "message" => [
        "id_pesan"   => (int) $row['id_pesan'],
        "from_me"    => true,
        "pesan"      => $row['pesan'],
        "waktu"      => date("H:i", strtotime($row['created_at'])),
        "created_at" => $row['created_at'],
    ],
]);
