<?php

require_once "../../config/database.php";
require_once "../../config/auth.php";

ownerOnly();

$pengirimId  = (int) $_SESSION['id_user'];
$penerimaId  = (int) $_POST['penerima_id'];
$pesan       = trim($_POST['pesan']);

if ($penerimaId > 0 && $pesan !== '') {

    $pesanEsc = mysqli_real_escape_string($conn, $pesan);

    mysqli_query($conn, "
        INSERT INTO chat_pesan (pengirim_id, penerima_id, pesan)
        VALUES ($pengirimId, $penerimaId, '$pesanEsc')
    ");

}

header("Location: ../../owner/chat.php?id=" . $penerimaId);
exit;