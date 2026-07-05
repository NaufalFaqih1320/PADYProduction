//=============================
// NAVBAR SCROLL
//=============================

const navbar = document.querySelector(".navbar");

window.addEventListener("scroll", function(){

    if(window.scrollY > 50){

        navbar.classList.add("active");

    }else{

        navbar.classList.remove("active");

    }

});

//======================================
// PORTFOLIO SLIDER
//======================================

const images = [

"assets/images/portfolio-1.png",
"assets/images/portfolio-2.jpg",
"assets/images/portfolio-3.png",
"assets/images/portfolio-4.jpg",
"assets/images/portfolio-5.png"

];

let index = 0;

const mainImage = document.getElementById("mainImage");

const thumbs = document.querySelectorAll(".thumb");

const dots = document.querySelectorAll(".dot");

function showImage(i){

    index = i;

    mainImage.classList.add("fade");

    setTimeout(()=>{

        mainImage.src = images[index];

        mainImage.classList.remove("fade");

    },180);

    thumbs.forEach(t=>t.classList.remove("active"));
    dots.forEach(d=>d.classList.remove("active"));

    thumbs[index].classList.add("active");
    dots[index].classList.add("active");

}

dots.forEach((dot,i)=>{

    dot.addEventListener("click",()=>{

        showImage(i);

    });

});

thumbs.forEach((thumb,i)=>{

    thumb.addEventListener("click",()=>{

        showImage(i);

    });

});

document.querySelector(".next").onclick=function(){

    index++;

    if(index>=images.length){

        index=0;

    }

    showImage(index);

}

document.querySelector(".prev").onclick=function(){

    index--;

    if(index<0){

        index=images.length-1;

    }

    showImage(index);

}

//======================================
// AUTO SLIDE
//======================================

setInterval(()=>{

    index++;

    if(index >= images.length){

        index = 0;

    }

    showImage(index);

},5000);

//============================
// ABOUT READ MORE
//============================

const readMoreBtn = document.getElementById("readMoreBtn");

const aboutMore = document.getElementById("aboutMore");

readMoreBtn.addEventListener("click",()=>{

    aboutMore.classList.toggle("show");

    if(aboutMore.classList.contains("show")){

        readMoreBtn.innerHTML="Tampilkan Lebih Sedikit";

    }else{

        readMoreBtn.innerHTML="Baca Selengkapnya";

    }

});