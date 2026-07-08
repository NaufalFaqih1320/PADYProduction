<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";
require_once "../../config/helpers.php";

clientOnly();

header("Content-Type: application/json");

$clientId = (int) $_SESSION['id_user'];
$ownerId  = getPrimaryOwnerId($conn);

if (!$ownerId) {
    echo json_encode([
        "success" => false,
        "message" => "Owner tidak ditemukan.",
    ]);
    exit;
}

/* ======================================
   TANDAI PESAN DARI OWNER SEBAGAI DIBACA
====================================== */

mysqli_query($conn, "
    UPDATE chat_pesan
    SET is_read = 1
    WHERE pengirim_id = $ownerId
      AND penerima_id = $clientId
      AND is_read = 0
");

/* ======================================
   AMBIL SEMUA PESAN
====================================== */

$since = isset($_GET['since_id']) ? (int) $_GET['since_id'] : 0;

$query = mysqli_query($conn, "
    SELECT id_pesan, pengirim_id, penerima_id, pesan, created_at
    FROM chat_pesan
    WHERE (pengirim_id = $clientId AND penerima_id = $ownerId)
       OR (pengirim_id = $ownerId AND penerima_id = $clientId)
    ORDER BY created_at ASC, id_pesan ASC
");

$messages = [];

while ($row = mysqli_fetch_assoc($query)) {

    $messages[] = [
        "id_pesan"   => (int) $row['id_pesan'],
        "from_me"    => ((int) $row['pengirim_id'] === $clientId),
        "pesan"      => $row['pesan'],
        "waktu"      => date("H:i", strtotime($row['created_at'])),
        "created_at" => $row['created_at'],
    ];

}

echo json_encode([
    "success"  => true,
    "owner_id" => $ownerId,
    "messages" => $messages,
]);
