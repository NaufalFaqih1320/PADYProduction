document.addEventListener("DOMContentLoaded", function () {

    /* ================================
       FILTER KATEGORI (PILL)
    ================================ */

    const pills = document.querySelectorAll(".pill");
    const cards = document.querySelectorAll(".inventaris-card[data-kategori]");

    pills.forEach(function (pill) {

        pill.addEventListener("click", function () {

            pills.forEach(p => p.classList.remove("active"));
            pill.classList.add("active");

            const kategori = pill.dataset.kategori;

            cards.forEach(function (card) {
                const cocok = kategori === "semua" || card.dataset.kategori === kategori;
                card.style.display = cocok ? "" : "none";
            });

        });

    });

    /* ================================
       MODAL TAMBAH INVENTARIS
    ================================ */

    const modal = document.getElementById("modalTambah");
    const btnBuka = document.getElementById("btnTambahInventaris");
    const btnTutup = document.getElementById("btnTutupModal");
    const btnBatal = document.getElementById("btnBatalModal");

    if (modal && btnBuka) {

        btnBuka.addEventListener("click", () => modal.classList.add("show"));

        [btnTutup, btnBatal].forEach(function (btn) {
            if (btn) {
                btn.addEventListener("click", () => modal.classList.remove("show"));
            }
        });

        modal.addEventListener("click", function (e) {
            if (e.target === modal) {
                modal.classList.remove("show");
            }
        });

    }

});