const header = document.querySelector("header");


window.addEventListener("scroll",function(){
    header.classList.toggle("sticky", this.window.scrollY>0 );
})


const productContainer = document.querySelector('.product-container-a');

function scrollLeft() {
    productContainer.scrollBy({
        left: -300,
        behavior: 'smooth'
    });
}

function scrollRight() {
    productContainer.scrollBy({
        left: 300,
        behavior: 'smooth'
    });
}
