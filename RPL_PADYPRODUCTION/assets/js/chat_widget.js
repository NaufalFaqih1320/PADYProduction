document.addEventListener("DOMContentLoaded", function () {

    const openBtn      = document.getElementById("openChatBtn");
    const overlay       = document.getElementById("chatOverlay");
    const drawer         = document.getElementById("chatDrawer");
    const closeBtn      = document.getElementById("chatDrawerClose");
    const messagesBox  = document.getElementById("chatDrawerMessages");
    const form                = document.getElementById("chatDrawerForm");
    const textarea         = document.getElementById("chatDrawerText");
    const sendBtn         = document.getElementById("chatDrawerSend");
    const badge              = document.getElementById("chatBtnBadge");

    if (!openBtn || !drawer) return;

    const BASE = window.CHAT_BASE_URL || "";

    let pollTimer = null;
    let badgeTimer = null;
    let lastMessageCount = 0;
    let isOpen = false;

    /* ================================
       RENDER PESAN
    ================================ */

    function renderMessages(messages) {

        if (!messages || messages.length === 0) {
            messagesBox.innerHTML =
                '<div class="chat-drawer-empty">Belum ada percakapan. Mulai chat dengan owner sekarang.</div>';
            lastMessageCount = 0;
            return;
        }

        if (messages.length === lastMessageCount) return;

        const wasAtBottom =
            messagesBox.scrollHeight - messagesBox.scrollTop - messagesBox.clientHeight < 40;

        let html = "";

        messages.forEach(function (msg) {

            const bubbleClass = msg.from_me ? "sent" : "received";

            html +=
                '<div class="chat-drawer-bubble ' + bubbleClass + '">' +
                    "<p>" + escapeHtml(msg.pesan) + "</p>" +
                    "<span>" + msg.waktu + "</span>" +
                "</div>";

        });

        messagesBox.innerHTML = html;
        lastMessageCount = messages.length;

        if (wasAtBottom || isOpen) {
            messagesBox.scrollTop = messagesBox.scrollHeight;
        }

    }

    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    /* ================================
       AMBIL PESAN (POLLING)
    ================================ */

    function fetchMessages() {

        fetch(BASE + "process/chat/get_pesan.php", { credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) {

                if (data.success) {
                    renderMessages(data.messages);
                    updateBadge(0);
                }

            })
            .catch(function () {});

    }

    /* ================================
       CEK PESAN BELUM DIBACA (SAAT DRAWER TERTUTUP)
    ================================ */

    function checkUnread() {

        if (isOpen) return;

        fetch(BASE + "process/chat/unread_count.php", { credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.success) updateBadge(data.unread);
            })
            .catch(function () {});

    }

    function updateBadge(count) {

        if (!badge) return;

        if (count > 0) {
            badge.textContent = count > 9 ? "9+" : count;
            badge.classList.add("show");
        } else {
            badge.classList.remove("show");
        }

    }

    /* ================================
       BUKA / TUTUP DRAWER
    ================================ */

    function openDrawer() {

        isOpen = true;
        overlay.classList.add("active");
        drawer.classList.add("active");
        updateBadge(0);

        fetchMessages();

        if (pollTimer) clearInterval(pollTimer);
        pollTimer = setInterval(fetchMessages, 3000);

        setTimeout(function () { textarea.focus(); }, 300);

    }

    function closeDrawer() {

        isOpen = false;
        overlay.classList.remove("active");
        drawer.classList.remove("active");

        if (pollTimer) clearInterval(pollTimer);

    }

    openBtn.addEventListener("click", function (e) {
        e.preventDefault();
        openDrawer();
    });

    closeBtn.addEventListener("click", closeDrawer);
    overlay.addEventListener("click", closeDrawer);

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && isOpen) closeDrawer();
    });

    /* ================================
       KIRIM PESAN
    ================================ */

    form.addEventListener("submit", function (e) {

        e.preventDefault();

        const pesan = textarea.value.trim();
        if (pesan === "") return;

        sendBtn.disabled = true;

        const body = new URLSearchParams();
        body.append("pesan", pesan);

        fetch(BASE + "process/chat/kirim_pesan_client.php", {
            method: "POST",
            credentials: "same-origin",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body.toString(),
        })
            .then(function (res) { return res.json(); })
            .then(function (data) {

                if (data.success) {
                    textarea.value = "";
                    autoGrow();
                    lastMessageCount = 0; // paksa re-render
                    fetchMessages();
                }

            })
            .catch(function () {})
            .finally(function () {
                sendBtn.disabled = false;
            });

    });

    textarea.addEventListener("keydown", function (e) {

        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            form.requestSubmit();
        }

    });

    function autoGrow() {
        textarea.style.height = "auto";
        textarea.style.height = Math.min(textarea.scrollHeight, 100) + "px";
    }

    textarea.addEventListener("input", autoGrow);

    /* ================================
       CEK UNREAD BERKALA (SAAT DRAWER TERTUTUP)
    ================================ */

    checkUnread();
    badgeTimer = setInterval(checkUnread, 8000);

});
