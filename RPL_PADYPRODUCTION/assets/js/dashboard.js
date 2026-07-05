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