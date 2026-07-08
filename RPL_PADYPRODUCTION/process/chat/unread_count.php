<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";
require_once "../../config/helpers.php";

clientOnly();

header("Content-Type: application/json");

$clientId = (int) $_SESSION['id_user'];
$ownerId  = getPrimaryOwnerId($conn);

if (!$ownerId) {
    echo json_encode(["success" => false, "unread" => 0]);
    exit;
}

$result = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM chat_pesan
    WHERE pengirim_id = $ownerId
      AND penerima_id = $clientId
      AND is_read = 0
");

$row = mysqli_fetch_assoc($result);

echo json_encode([
    "success" => true,
    "unread"  => (int) $row['total'],
]);
