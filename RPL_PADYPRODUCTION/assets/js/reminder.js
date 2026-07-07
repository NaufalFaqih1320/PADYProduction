document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("searchReminder");
    const cards = document.querySelectorAll(".reminder-card[data-search]");

    if (searchInput) {

        searchInput.addEventListener("keyup", function () {

            const keyword = this.value.toLowerCase();

            cards.forEach(function (card) {
                card.style.display = card.dataset.search.includes(keyword) ? "" : "none";
            });

        });

    }

});