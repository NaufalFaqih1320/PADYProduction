document.addEventListener("DOMContentLoaded", function () {

    const tabs = document.querySelectorAll(".tab");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach((tab, index) => {

        tab.addEventListener("click", () => {

            tabs.forEach(t => t.classList.remove("active"));
            contents.forEach(c => c.style.display = "none");

            tab.classList.add("active");
            contents[index].style.display = "block";

        });

    });

});

const modal = document.getElementById("bookingModal");

const btn = document.querySelector(".btn-add");

const close = document.getElementById("closeModal");

btn.addEventListener("click", () => {

    modal.style.display = "flex";

});

close.addEventListener("click", () => {

    modal.style.display = "none";

});

window.onclick = function(e){

    if(e.target==modal){

        modal.style.display="none";

    }

}

/* ================================
   SEARCH BOOKING
================================ */

const searchInput = document.getElementById("searchBooking");

if (searchInput) {

    searchInput.addEventListener("keyup", function () {

        const keyword = this.value.toLowerCase();

        const cards = document.querySelectorAll(".booking-card");

        cards.forEach(card => {

            const data = card.dataset.search;

            if (data.includes(keyword)) {

                card.style.display = "";

            } else {

                card.style.display = "none";

            }

        });

    });

}