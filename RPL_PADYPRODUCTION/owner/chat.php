<?php

require_once "../config/database.php";
require_once "../config/auth.php";

ownerOnly();

$ownerId = (int) $_SESSION['id_user'];

/* ======================================
   DAFTAR KONTAK (CLIENT)
====================================== */

$queryKontak = mysqli_query($conn, "
    SELECT
        u.id_user,
        u.nama,
        u.email,
        (SELECT pesan FROM chat_pesan
            WHERE (pengirim_id = u.id_user AND penerima_id = $ownerId)
               OR (pengirim_id = $ownerId AND penerima_id = u.id_user)
            ORDER BY created_at DESC LIMIT 1) AS pesan_terakhir,
        (SELECT created_at FROM chat_pesan
            WHERE (pengirim_id = u.id_user AND penerima_id = $ownerId)
               OR (pengirim_id = $ownerId AND penerima_id = u.id_user)
            ORDER BY created_at DESC LIMIT 1) AS waktu_terakhir,
        (SELECT COUNT(*) FROM chat_pesan
            WHERE pengirim_id = u.id_user
              AND penerima_id = $ownerId
              AND is_read = 0) AS belum_dibaca
    FROM users u
    WHERE u.role = 'client'
    ORDER BY waktu_terakhir DESC
");

/* ======================================
   KONTAK AKTIF
====================================== */

$activeId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($activeId > 0) {

    mysqli_query($conn, "
        UPDATE chat_pesan
        SET is_read = 1
        WHERE pengirim_id = $activeId
          AND penerima_id = $ownerId
          AND is_read = 0
    ");

    $activeUser = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT id_user, nama, email
        FROM users
        WHERE id_user = $activeId AND role = 'client'
    "));

    $queryPesan = mysqli_query($conn, "
        SELECT *
        FROM chat_pesan
        WHERE (pengirim_id = $activeId AND penerima_id = $ownerId)
           OR (pengirim_id = $ownerId AND penerima_id = $activeId)
        ORDER BY created_at ASC
    ");

} else {

    $activeUser = null;
    $queryPesan = null;

}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Owner</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/chat.css">
</head>
<body>

<div class="wrapper">

    <?php include "../includes/layout/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/layout/topbar.php"; ?>

        <div class="content-box chat-content-box">

            <h3>Chat dengan Client</h3>

            <div class="chat-wrapper">

                <div class="chat-sidebar">

                    <div class="search-box chat-search">
                        <input
                            type="text"
                            id="searchChat"
                            placeholder="Cari nama atau email client...">
                    </div>

                    <div class="chat-list">

                        <?php if (mysqli_num_rows($queryKontak) > 0): ?>

                            <?php while ($kontak = mysqli_fetch_assoc($queryKontak)): ?>

                                <a
                                    href="chat.php?id=<?= $kontak['id_user']; ?>"
                                    class="chat-item <?= ($kontak['id_user'] == $activeId) ? 'active' : ''; ?>"
                                    data-search="<?= strtolower($kontak['nama'] . ' ' . $kontak['email']); ?>">

                                    <div class="chat-item-info">
                                        <h4><?= htmlspecialchars($kontak['nama']); ?></h4>
                                        <p>
                                            <?= $kontak['pesan_terakhir']
                                                ? htmlspecialchars(mb_strimwidth($kontak['pesan_terakhir'], 0, 40, '...'))
                                                : 'Belum ada percakapan'; ?>
                                        </p>
                                    </div>

                                    <?php if ($kontak['belum_dibaca'] > 0): ?>
                                        <span class="chat-badge"><?= $kontak['belum_dibaca']; ?></span>
                                    <?php endif; ?>

                                </a>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <div class="chat-item-empty">Belum ada client terdaftar.</div>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="chat-window">

                    <?php if ($activeUser): ?>

                        <div class="chat-window-header">
                            <h4><?= htmlspecialchars($activeUser['nama']); ?></h4>
                            <small><?= htmlspecialchars($activeUser['email']); ?></small>
                        </div>

                        <div class="chat-messages" id="chatMessages">

                            <?php while ($pesan = mysqli_fetch_assoc($queryPesan)): ?>

                                <div class="bubble <?= $pesan['pengirim_id'] == $ownerId ? 'sent' : 'received'; ?>">
                                    <p><?= nl2br(htmlspecialchars($pesan['pesan'])); ?></p>
                                    <span><?= date('H:i', strtotime($pesan['created_at'])); ?></span>
                                </div>

                            <?php endwhile; ?>

                        </div>

                        <form
                            class="chat-input-box"
                            action="../process/chat/kirim_pesan.php"
                            method="POST">

                            <input type="hidden" name="penerima_id" value="<?= $activeUser['id_user']; ?>">

                            <textarea
                                name="pesan"
                                id="chatInput"
                                placeholder="Tulis pesan..."
                                required></textarea>

                            <button type="submit">Kirim</button>

                        </form>

                    <?php else: ?>

                        <div class="chat-empty-state">
                            <p>Pilih client di samping untuk mulai chat.</p>
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="../assets/js/chat.js"></script>

</body>
</html>