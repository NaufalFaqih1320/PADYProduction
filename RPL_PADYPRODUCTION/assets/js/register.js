const password = document.getElementById("password");
const konfirmasi = document.getElementById("konfirmasi");

konfirmasi.addEventListener("keyup", function(){

    if(password.value !== konfirmasi.value){

        konfirmasi.style.borderColor="red";

    }else{

        konfirmasi.style.borderColor="green";

    }

});