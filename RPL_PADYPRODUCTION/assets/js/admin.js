document.addEventListener("DOMContentLoaded", function () {

    /* ================================
       GENERIC MODAL OPEN/CLOSE
       Elemen pemicu buka  : [data-open-modal="idModal"]
       Elemen pemicu tutup : [data-close-modal="idModal"]
    ================================ */

    document.querySelectorAll("[data-open-modal]").forEach(function (btn) {

        btn.addEventListener("click", function () {

            const modal = document.getElementById(btn.dataset.openModal);
            if (!modal) return;

            modal.classList.add("show");

        });

    });

    document.querySelectorAll("[data-close-modal]").forEach(function (btn) {

        btn.addEventListener("click", function () {

            const modal = document.getElementById(btn.dataset.closeModal);
            if (!modal) return;

            modal.classList.remove("show");

        });

    });

    document.querySelectorAll(".modal-overlay").forEach(function (modal) {

        modal.addEventListener("click", function (e) {

            if (e.target === modal) {
                modal.classList.remove("show");
            }

        });

    });

    /* ================================
       EDIT PENGGUNA: ISI MODAL DARI data-*
    ================================ */

    document.querySelectorAll(".btn-edit-user").forEach(function (btn) {

        btn.addEventListener("click", function () {

            const modal = document.getElementById("modalEditUser");
            if (!modal) return;

            modal.querySelector('[name="id_user"]').value  = btn.dataset.id;
            modal.querySelector('[name="nama"]').value     = btn.dataset.nama;
            modal.querySelector('[name="email"]').value    = btn.dataset.email;
            modal.querySelector('[name="no_hp"]').value    = btn.dataset.noHp || "";
            modal.querySelector('[name="role"]').value     = btn.dataset.role;
            modal.querySelector('[name="status"]').value   = btn.dataset.status;

            modal.classList.add("show");

        });

    });

    /* ================================
       EDIT INVENTARIS: ISI MODAL DARI data-*
    ================================ */

    document.querySelectorAll(".btn-edit-inventaris").forEach(function (btn) {

        btn.addEventListener("click", function () {

            const modal = document.getElementById("modalEditInventaris");
            if (!modal) return;

            modal.querySelector('[name="id_inventaris"]').value = btn.dataset.id;
            modal.querySelector('[name="nama_barang"]').value   = btn.dataset.namaBarang;
            modal.querySelector('[name="id_kategori"]').value   = btn.dataset.idKategori || "";
            modal.querySelector('[name="stok"]').value          = btn.dataset.stok;
            modal.querySelector('[name="kondisi"]').value       = btn.dataset.kondisi;
            modal.querySelector('[name="lokasi"]').value        = btn.dataset.lokasi || "";
            modal.querySelector('[name="keterangan"]').value    = btn.dataset.keterangan || "";

            modal.classList.add("show");

        });

    });

    /* ================================
       FILTER KATEGORI (PILL) - INVENTARIS
    ================================ */

    const pills = document.querySelectorAll(".pill[data-kategori]");
    const invCards = document.querySelectorAll(".inventaris-card[data-kategori]");

    pills.forEach(function (pill) {

        pill.addEventListener("click", function () {

            pills.forEach(p => p.classList.remove("active"));
            pill.classList.add("active");

            const kategori = pill.dataset.kategori;

            invCards.forEach(function (card) {
                const cocok = kategori === "semua" || card.dataset.kategori === kategori;
                card.style.display = cocok ? "" : "none";
            });

        });

    });

    /* ================================
       PENGGUNA: SEARCH + FILTER ROLE
    ================================ */

    const searchUser = document.getElementById("searchUser");
    const rolePills = document.querySelectorAll("#rolePills .pill");
    const userRows = document.querySelectorAll(".user-row[data-role]");

    function filterUser() {

        const keyword = (searchUser?.value || "").toLowerCase().trim();
        const activePill = document.querySelector("#rolePills .pill.active");
        const role = activePill ? activePill.dataset.role : "semua";

        userRows.forEach(function (row) {

            const cocokRole = role === "semua" || row.dataset.role === role;
            const cocokSearch = keyword === "" || row.dataset.search.includes(keyword);

            row.style.display = (cocokRole && cocokSearch) ? "" : "none";

        });

    }

    if (searchUser) {
        searchUser.addEventListener("input", filterUser);
    }

    rolePills.forEach(function (pill) {

        pill.addEventListener("click", function () {
            rolePills.forEach(p => p.classList.remove("active"));
            pill.classList.add("active");
            filterUser();
        });

    });

    /* ================================
       BOOKING: SEARCH + FILTER STATUS
    ================================ */

    const searchBookingAdmin = document.getElementById("searchBookingAdmin");
    const statusPillsBookingAdmin = document.querySelectorAll("#statusPillsAdmin .pill");
    const bookingAdminCards = document.querySelectorAll(".booking-crew-card[data-status]");

    function filterBookingAdmin() {

        const keyword = (searchBookingAdmin?.value || "").toLowerCase().trim();
        const activePill = document.querySelector("#statusPillsAdmin .pill.active");
        const status = activePill ? activePill.dataset.status : "semua";

        bookingAdminCards.forEach(function (card) {

            const cocokStatus = status === "semua" || card.dataset.status === status;
            const cocokSearch = keyword === "" || card.dataset.search.includes(keyword);

            card.style.display = (cocokStatus && cocokSearch) ? "" : "none";

        });

    }

    if (searchBookingAdmin) {
        searchBookingAdmin.addEventListener("input", filterBookingAdmin);
    }

    statusPillsBookingAdmin.forEach(function (pill) {

        pill.addEventListener("click", function () {
            statusPillsBookingAdmin.forEach(p => p.classList.remove("active"));
            pill.classList.add("active");
            filterBookingAdmin();
        });

    });

});
