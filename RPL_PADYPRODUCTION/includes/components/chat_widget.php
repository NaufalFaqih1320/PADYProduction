<?php
/*
    CHAT WIDGET — CLIENT
    Drawer overlay "Hubungi Kami" yang muncul menimpa sebagian halaman
    (bukan halaman penuh). Hanya dirender jika user login sebagai client.
*/
?>

<div class="chat-overlay" id="chatOverlay"></div>

<div class="chat-drawer" id="chatDrawer">

    <div class="chat-drawer-header">

        <div class="chat-drawer-title">
            <span class="chat-drawer-avatar"><i class="fa-solid fa-headset"></i></span>
            <div>
                <h4>Hubungi Kami</h4>
                <small>PADY Production &mdash; Owner</small>
            </div>
        </div>

        <button type="button" class="chat-drawer-close" id="chatDrawerClose" aria-label="Tutup">
            <i class="fa-solid fa-xmark"></i>
        </button>

    </div>

    <div class="chat-drawer-messages" id="chatDrawerMessages">
        <div class="chat-drawer-loading">Memuat percakapan...</div>
    </div>

    <form class="chat-drawer-input" id="chatDrawerForm">

        <textarea
            id="chatDrawerText"
            placeholder="Tulis pesan..."
            rows="1"
            required></textarea>

        <button type="submit" id="chatDrawerSend" aria-label="Kirim">
            <i class="fa-solid fa-paper-plane"></i>
        </button>

    </form>

</div>
