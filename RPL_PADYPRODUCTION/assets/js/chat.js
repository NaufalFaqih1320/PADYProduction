document.addEventListener("DOMContentLoaded", function () {

    /* ================================
       SCROLL OTOMATIS KE PESAN TERBARU
    ================================ */

    const chatMessages = document.getElementById("chatMessages");

    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    /* ================================
       CARI KONTAK
    ================================ */

    const searchChat = document.getElementById("searchChat");
    const chatItems = document.querySelectorAll(".chat-item[data-search]");

    if (searchChat) {

        searchChat.addEventListener("keyup", function () {

            const keyword = this.value.toLowerCase();

            chatItems.forEach(function (item) {
                item.style.display = item.dataset.search.includes(keyword) ? "" : "none";
            });

        });

    }

    /* ================================
       ENTER UNTUK KIRIM (SHIFT+ENTER = BARIS BARU)
    ================================ */

    const chatInput = document.getElementById("chatInput");

    if (chatInput) {

        chatInput.addEventListener("keydown", function (e) {

            if (e.key === "Enter" && !e.shiftKey) {

                e.preventDefault();

                const form = chatInput.closest("form");

                if (form && chatInput.value.trim() !== "") {
                    form.submit();
                }

            }

        });

    }

});