






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adidas Clone</title>
    <link rel="icon" href="../images/Adidas-Logo.png"
    type="text/x-icon">
    
    <link rel="stylesheet" href="../assets/css/bag.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet"
    href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  
</head>
<body>


  <div class="bag">
    <div class="bag-container">
        <div class="header">
            <h1>Your Bag</h1>
            <p>Total: (1 item) <strong>$22.39</strong></p>
            <p>Items in your bag are not reserved — check out now to make them yours.</p>
        </div>

        <div class="sale-banner">
            <p><strong>BLACK FRIDAY</strong></p>
            <p>Save up to 70% during the Black Friday sale, no code needed. Ends 11/30.</p>
        </div>

        <div class="product">
            <img src="../images/11.png" alt="Product" class="product-image">
            <div class="product-info">
                <p><strong>ESSENTIALS 3-STRIPES FLEECE PANTS</strong></p>
                <p>BLACK / WHITE</p>
                <p>Size: XL</p>
                <p class="price">
                <span class="original-price">$50.00</span> <span class="sale-price">$15.00</span>
                </p>

                <div class="quantity">
                    <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                    <input type="text" id="quantity-input" value="1" readonly>
                    <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                  </div>
                  
            </div>
            
            <div class="bag-icon">
                <i class='bx bx-x'></i>
            </div>

        </div>

        <div class="product">
            <img src="../images/12.png" alt="Product" class="product-image">
            <div class="product-info">
                <p><strong>ESSENTIALS 3-STRIPES FLEECE PANTS</strong></p>
                <p>BLACK / WHITE</p>
                <p>Size: XL</p>
                <p class="price">
                <span class="original-price">$50.00</span> <span class="sale-price">$15.00</span>
                </p>

                <div class="quantity">
                    <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                    <input type="text" id="quantity-input" value="1" readonly>
                    <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                  </div>
                  
            </div>
            
            <div class="bag-icon">
                <i class='bx bx-x'></i>
            </div>

        </div>

        <div class="product">
            <img src="../images/13.png" alt="Product" class="product-image">
            <div class="product-info">
                <p><strong>ESSENTIALS 3-STRIPES FLEECE PANTS</strong></p>
                <p>BLACK / WHITE</p>
                <p>Size: XL</p>
                <p class="price">
                <span class="original-price">$50.00</span> <span class="sale-price">$15.00</span>
                </p>

                <div class="quantity">
                    <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                    <input type="text" id="quantity-input" value="1" readonly>
                    <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                  </div>
            </div>
            
            <div class="bag-icon">
                <i class='bx bx-x'></i>
            </div>
        </div>

    </div>
    
    <div class="order-summary">
        <h3>Order Summary</h3>
        <ul>
          <li>1 item <span>$15.00</span></li>
          <li>Original price <span>$50.00</span></li>
          <li>Sales Tax <span>$2.40</span></li>
          <li>Delivery <span>$4.99</span></li>
          <li>Sale <span>-$35.00</span></li>
        </ul>
        <h4>Total <span>$22.39</span></h4>
        <p><strong>4 interest-free payments of $5.59 with Klarna.</strong></p>

        <a href="login1.html">
            <button class="checkout-btn">Checkout</button>
        </a>

        <button class="gpay-btn">G Pay</button>

        <div class="pay">

            <p>Accepted payment methods</p>
            <div class="pay-img">
                <img src="../images/American-Express-Color.png" alt=""> 
                <img src="../images/visa.png" alt="">
                <img src="../images/Mastercard_2019_logo.svg.png" alt="">
            </div>
        </div>
    </div>


</div>

</body>
</html>
