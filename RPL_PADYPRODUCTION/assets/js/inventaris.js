/* ===========================================
   INVENTARIS OWNER
=========================================== */

document.addEventListener("DOMContentLoaded", function () {

    /* ===================================
       SEARCH REALTIME
    =================================== */

    const searchInput = document.querySelector("input[name='search']");

    if (searchInput) {

        searchInput.addEventListener("keyup", function () {

            let keyword = this.value.toLowerCase();

            let rows = document.querySelectorAll(".inventory-table tbody tr");

            rows.forEach(function (row) {

                let text = row.innerText.toLowerCase();

                if (text.indexOf(keyword) > -1) {

                    row.style.display = "";

                } else {

                    row.style.display = "none";

                }

            });

        });

    }

    /* ===================================
       HOVER EFFECT
    =================================== */

    const cards = document.querySelectorAll(".summary-card");

    cards.forEach(function (card) {

        card.addEventListener("mouseenter", function () {

            card.style.transform = "translateY(-5px)";
            card.style.transition = ".3s";

        });

        card.addEventListener("mouseleave", function () {

            card.style.transform = "translateY(0px)";

        });

    });

    /* ===================================
       PREVIEW FOTO
    =================================== */

    const images = document.querySelectorAll(".inventory-table img");

    images.forEach(function (img) {

        img.style.cursor = "pointer";

        img.addEventListener("click", function () {

            let preview = document.createElement("div");

            preview.classList.add("preview-image");

            preview.innerHTML = `

                <div class="preview-background">

                    <img src="${this.src}">

                </div>

            `;

            document.body.appendChild(preview);

            preview.addEventListener("click", function () {

                preview.remove();

            });

        });

    });

    /* ===================================
       SORTING
    =================================== */

    const headers = document.querySelectorAll(".inventory-table th");

    headers.forEach(function (header, index) {

        header.style.cursor = "pointer";

        header.addEventListener("click", function () {

            sortTable(index);

        });

    });

});

/* ===========================================
   SORT TABLE
=========================================== */

function sortTable(column) {

    let table = document.querySelector(".inventory-table table");

    let rows = Array.from(table.rows).slice(1);

    let asc = table.getAttribute("data-sort") !== "asc";

    rows.sort(function (a, b) {

        let x = a.cells[column].innerText.toLowerCase();

        let y = b.cells[column].innerText.toLowerCase();

        if (!isNaN(x) && !isNaN(y)) {

            return asc ? x - y : y - x;

        }

        return asc
            ? x.localeCompare(y)
            : y.localeCompare(x);

    });

    rows.forEach(function (row) {

        table.tBodies[0].appendChild(row);

    });

    table.setAttribute("data-sort", asc ? "asc" : "desc");

}