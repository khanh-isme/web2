<?php
include './includes/header.php';
?>

//hiiii
<?php
include './includes/config.php';
$products = getProductList($connection);
$categories = getCategoryList($connection);
?>
<html>
       
   <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <link rel="stylesheet" href="/web2/assets/css/home.css">
        
        <script src="/web2/assets/js/header.js"></script>

        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

        <link rel="stylesheet"
        href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
        <title>Document</title>
    </head>
   
   <body>
    
       <section class="main-home">
            <div class="main-text">
                <h2> Winter Collection </h2>
            
                <p> there's is nothing </p>
    
                <a href="/web2/pages/shop.php" class="main-btn"> Shop Now <i class='bx bx-right-arrow-alt'></i> </a>
                
            </div>
            
            <div class="down-arrow">
                <a href="#trending" class="down"><i class='bx bx-down-arrow-alt'></i></a>
            </div>
    
        </section>
    
    
    
        <!-- phần New Arrivals Just Landed-->
        <div class="product-section-a">
            <h2>New Arrivals Just Landed</h2>
            <div class="product-container-a" id="product-list"></div>
        </div>
    
        <script>
            const products = <?php echo json_encode($products); ?>;
            console.log(products);
            const categories = <?php echo json_encode($categories); ?>;
            console.log(categories);
            const productContainer = document.getElementById("product-list");
            const product_copy = products.slice(0, 5);
            
            product_copy.forEach(product => {
                const category = categories.find(category => category.id === product.category_id);
                const categoryName = category ? category.name : "Unknown"; 

                const productHTML = `
                    <div class="product-a">
                        <a href="product.html">
                            <img src="${product.image}" alt="${product.name}">
                            <h3>${product.name}</h3>
                            <p>${categoryName}</p>
                            <p class="price">${product.price}</p>
                        </a>
                    </div>
                `;
                productContainer.innerHTML += productHTML;
            });


        </script>
    





        <!--------------------------------------------- Sale Product Section ------------------------------------------>
        <section class="trending-product" id="trending">
            <div class="center-text">
                <h2> Our Sale <span> Products</span></h2>
            </div>

            <div class="product-container" id="product-list-1"></div>
        </section>

        <script>
            
            const product_sale = products.slice(5, 10);
            console.log(product_sale);
            const productContainer1 = document.getElementById("product-list-1");

            product_sale.forEach(product => {
                const productHTML = `
                    <div class="product">
                        <a href="product.html">
                            <img src="${product.image}" alt="${product.name}">
                        </a>
                        <div class="product-text">
                            <h5> Sale</h5>
                        </div>
                        <div class="heart-icon">
                            <i class='bx bx-heart'></i>
                        </div>
                        <div class="ratting">
                            <i class='bx bx-star'></i>
                            <i class='bx bx-star'></i>
                            <i class='bx bx-star'></i>
                            <i class='bx bx-star'></i>
                            <i class='bx bxs-star-half'></i>
                        </div>
                        <div class="price">
                            <h4>${product.name}</h4>
                            <p>${product.price}</p>
                        </div>
                    </div>
                `;
                productContainer1.innerHTML += productHTML;
            });

         </script>   
    
    
        
    
    
    
    <!--client review  section -->
        <section class="client-review">
            <div class="review">
                <h3> Client review</h3>
                <img src="/web2/assets/images/30.jpeg" alt="">
                <p>
                    Everything you love about the AF-1—but doubled! The Air Force 1 <br>
                    Shadow puts a playful twist on a classic basketball design. <br>Layered materials bring depth, 
                    while an exaggerated midsole and double the branding give <br>these sneakers a fresh look with twice the style.
                </p>
    
            </div>
        </section>
    
    
        <!--THE LASTED-->
        <div class="the-lasted-head" id="the-lasted">
            <h2> THE LASTED</h2>
        </div>
    

        <div class="the-lasted-section">
            <div class="the-lasted">
                <img src="/web2/assets/images/23.jpg" alt="">
                <div class="the-lasted-text">
                    <h4>run in the rain</h4>
                    <h1>Ai sợ đi về</h1>
                    <a href="shop.html" class="the-lasted-btn"> Shop Now <i class='bx bx-right-arrow-alt'></i> </a>
                </div>
            </div>
    
            <div class="the-lasted">
                <img src="/web2/assets/images/29.jpg" alt="">
                <div class="the-lasted-text">
                    <h4>run in the rain</h4>
                    <h1>NIKE em iu thich</h1>
                    <a href="shop.html" class="the-lasted-btn"> Shop Now <i class='bx bx-right-arrow-alt'></i> </a>
                </div>
            </div>
    
            <div class="the-lasted">
                <img src="/web2/assets/images/25.jpg" alt="">
                <div class="the-lasted-text">
                    <h4>run in the rain</h4>
                    <h1>NIKE em iu thich</h1>
                    <a href="shop.html" class="the-lasted-btn"> Shop Now <i class='bx bx-right-arrow-alt'></i> </a>
                </div>
            </div>
    
        </div>
    
    
    
        <!--sport-->
        <div class="product-section-sport">
            <div class="product-section-sport-text">
                <p>in your live</p>
                <h1> SPORT</h1>
                <p>with nike , tomorrow will be even later</p>
                <h2> NOW </h2>
            </div>
    
            <div class="product-container-sport">
                <div class="product-sport">
    
                    <img src="/web2/assets/images/42.jpg" alt="Nike C1TY 'Brownstone'">
                    <a class="sport-text" href="shop.html"> basketball </a>
                    
                </div>
                <div class="product-sport">
                    <img src="/web2/assets/images/24.jpg" alt="Nike Air Force 1 Shadow">
                    <a class="sport-text" href="shop.html"> naiii </a>
                </div>
    
                <div class="product-sport">
                    <img src="/web2/assets/images/43.jpg" alt="Nike Zoom Vomero Roam">
                    <a class="sport-text" href="shop.html"> baseball </a>
                </div>
    
                <div class="product-sport">
                    <img src="/web2/assets/images/44.jpg" alt="Nike Zoom Vomero Roam">
                    <a class="sport-text" href="shop.html"> never give up</a>
                </div>
    
                <div class="product-sport">
                    <img src="/web2/assets/images/45.jpg" alt="Nike Zoom Vomero Roam">
                    <a class="sport-text" href="shop.html"> nhảy đê</a>
                </div>
                
                <div class="product-sport">
                    <img src="/web2/assets/images/46.png" alt="Nike Zoom Vomero Roam">
                    <a class="sport-text" href="shop.html"> mưa vãi</a>
                </div>
    
                
                <!-- Thêm các sản phẩm khác ở đây -->
            </div>
            
        </div>
    
    
        <!--update new section-->
        <section class="update-new">
            <div class="up-center-text">
                <h2> new update </h2>
            </div>
    
            <div class="update-cart">
                <a href="shop.html">
    
                    <div class="cart">
                        <img src="/web2/assets/images/banner5.jpg" alt="">
                        <h5> 26 jan  2024 </h5>
                        <h4> let start bring sale on this summer vacation</h4>
                        <p>Everything you love about the AF-1—but doubled! The Air Force 1 
                            Shadow puts a playful twist on a classic basketball design. Layered materials bring depth, 
                            while an exaggerated midsole and double the branding give these sneakers a fresh look with twice the style.
                        </p>
    
                        <h6> continue reading..... </h6>
                    </div>
                </a>
                
                <a href="shop.html">
    
                    <div class="cart">
                        <img src="/web2/assets/images/banner5.jpg" alt="">
                        <h5> 26 jan  2024 </h5>
                        <h4> let start bring sale on this summer vacation</h4>
                        <p>Everything you love about the AF-1—but doubled! The Air Force 1 
                            Shadow puts a playful twist on a classic basketball design. Layered materials bring depth, 
                            while an exaggerated midsole and double the branding give these sneakers a fresh look with twice the style.
                        </p>
    
                        <h6> continue reading.... </h6>
                    </div>
                </a>
    
    
                <a href="shop.html">
                    
                    <div class="cart">
                        <img src="/web2/assets/images/banner5.jpg" alt="">
                        <h5> 26 jan  2024 </h5>
                        <h4> let start bring sale on this summer vacation</h4>
                        <p>Everything you love about the AF-1—but doubled! The Air Force 1 
                            Shadow puts a playful twist on a classic basketball design. Layered materials bring depth, 
                            while an exaggerated midsole and double the branding give these sneakers a fresh look with twice the style.
                        </p>
    
                        <h6> continue reading.....</h6>
                    </div>
                </a>
    
            </div>
        </section>


        
   </body>
   

</html>


<?php
include './includes/footer.php';
?>