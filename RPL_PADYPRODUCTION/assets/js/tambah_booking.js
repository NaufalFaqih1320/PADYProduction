document.addEventListener("DOMContentLoaded", function () {

    const cards = document.querySelectorAll(".inventory-card");

    cards.forEach(card => {

        const minus = card.querySelector(".minus");
        const plus = card.querySelector(".plus");
        const jumlah = card.querySelector(".jumlah");
        const input = card.querySelector(".qty-input");

        let value = parseInt(input.value) || 0;
        const max = parseInt(plus.dataset.max);

        // Kondisi awal
        minus.disabled = (value === 0);
plus.disabled = (value >= max);

if (value > 0) {
    card.classList.add("active");
}

        // Tombol tambah
        plus.addEventListener("click", function () {

    if (value < max) {

        value++;

        jumlah.textContent = value;
        input.value = value;

    }

    minus.disabled = (value === 0);
    plus.disabled = (value >= max);

    if (value > 0) {
        card.classList.add("active");
    }

});

        // Tombol kurang
        minus.addEventListener("click", function () {

    if (value > 0) {

        value--;

        jumlah.textContent = value;
        input.value = value;

    }

    minus.disabled = (value === 0);
    plus.disabled = (value >= max);

    if (value === 0) {
        card.classList.remove("active");
    }

});

    });

});