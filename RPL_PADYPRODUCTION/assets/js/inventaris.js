document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("searchInventaris");
    const filterKondisi = document.getElementById("filterKondisi");
    const cards = document.querySelectorAll(".inventory-card[data-search]");

    function terapkanFilter() {

        const keyword = searchInput ? searchInput.value.toLowerCase() : "";
        const kondisi = filterKondisi ? filterKondisi.value : "";

        cards.forEach(function (card) {

            const cocokKeyword = card.dataset.search.includes(keyword);
            const cocokKondisi = kondisi === "" || card.dataset.kondisi === kondisi;

            card.style.display = (cocokKeyword && cocokKondisi) ? "" : "none";

        });

    }

    if (searchInput) {
        searchInput.addEventListener("keyup", terapkanFilter);
    }

    if (filterKondisi) {
        filterKondisi.addEventListener("change", terapkanFilter);
    }

    /* ================================
       Contoh pola untuk fitur khusus role lain:
       tinggal tambah blok baru di sini, dibungkus
       pengecekan elemen, tanpa ganggu role lain.
    ================================ */

    // Contoh: tombol hapus/edit yang cuma ada di admin
    const btnHapus = document.querySelectorAll(".inventory-card .btn-delete");
    btnHapus.forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            if (!confirm("Yakin ingin menghapus barang ini?")) {
                e.preventDefault();
            }
        });
    });

});