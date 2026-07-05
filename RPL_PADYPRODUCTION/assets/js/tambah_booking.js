document.querySelectorAll(".inventory-card").forEach(card => {

    const qtyBox = card.querySelector(".qty-box");

    const max = parseInt(qtyBox.dataset.stock);

    const minus = card.querySelector(".minus");
    const plus = card.querySelector(".plus");
    const jumlah = card.querySelector(".jumlah");
    const hidden = card.querySelector("input");

    let value = 0;

    function updateUI(){

        jumlah.textContent = value;
        hidden.value = value;

        minus.disabled = value === 0;
        plus.disabled = value === max;

    }

    plus.addEventListener("click", () => {

        if(value < max){

            value++;

            updateUI();

        }

    });

    minus.addEventListener("click", () => {

        if(value > 0){

            value--;

            updateUI();

        }

    });

    updateUI();

});