document.addEventListener("DOMContentLoaded", function () {

    const btnCetak = document.getElementById("btnCetak");

    if (btnCetak) {

        btnCetak.addEventListener("click", function () {
            window.print();
        });

    }

});