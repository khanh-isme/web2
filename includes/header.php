<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/header.css">
    <script src="./assets/js/header.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet"
    href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <title>Document</title>
</head>
<body>
    <header>
        <a href="index.html" class ="logo"> <img src="../images/Adidas-Logo.png" alt=""></a>
        
        <ul class="nav-menu">
            <li> <a href="/web2/pages/home.php"> home</a></li>
            <li><a href="/web2/pages/shop.php">shop</a></li>

                    
            <li> <a href=""> page </a></li>
            <li> <a href=""> Sale</a></li>
        </ul>
        
        <!--thẻ <i> là thẻ chứ biểu tượng -->
        <div class="nav-icon">
            
            <div class="search-icon"><i class='bx bx-search'  onclick="toggleSearch()"></i></div>

            <a href = "../html/login.html" ><i class='bx bx-user'></i></a>


            <div class="cart-icon" onclick="toggleCartPopup()">

                <i class='bx bx-cart' id="cart-icon"></i>
            </div>
            
            <div class= "bx bx-menu" id ="menu-icon"></div>
        </div>

    </header>


    <!-- Cart Popup -->
    <div class="cart-popup" id="cartPopup">
        <div class="cart-item">
            <img src="../images/13.png" alt="Product Image">
            <div class="cart-item-details">
                <div class="cart-item-name">T-Shirt 14A</div>
                <div class="cart-item-price">$50.05 x1</div>
            </div>
            <div class="cart-item-delete" onclick="removeItem()">
                <i class='bx bxs-trash-alt' ></i>
            </div>
        </div>

        <div class="cart-item">
            <img src="../images/14.png" alt="Product Image">
            <div class="cart-item-details">
                <div class="cart-item-name">T-Shirt 14A</div>
                <div class="cart-item-price">$50.05 x1</div>
            </div>
            <div class="cart-item-delete" onclick="removeItem()">
                <i class='bx bxs-trash-alt' ></i>
            </div>
        </div>

        <a href="bag.html">
            <p>see more...</p>
        </a>


        <div class="cart-total">Total: $100.10</div>
        <a href="bag.html">

            <button class="checkout-btn">CHECK YOUR BAG</button>
        </a>
    </div>



<!--tìm kiếm nâng cao-->    
    <div class="search-container" id="searchPopup">
        <h2>Advanced Product Search</h2>
        
        <!-- Search Form -->
        <form id="searchForm">
        <!-- Product Name Field -->
        <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" id="productName" name="productName" placeholder="Enter product name...">
        </div>
        
        <!-- Category Selection -->
        <div class="form-group">
        <label for="category">Category</label>
        <select id="category" name="category">
            <option value="">Select category</option>
            <option value="shoes">Sport</option>
            
            <option value="fastion">fastion</option>
        
            <!-- Add more categories as needed -->
        </select>
        </div>

        <div class="form-group">
        <label for="category">Collection</label>
        <select id="category" name="category">
            <option value="">Select collection</option>
            <option value="">winter</option>
            <option value="shoes">summer</option>
            <option value="nike-air">spring</option>

            <!-- Add more categories as needed -->
        </select>
        </div>

        <div class="form-group">
            <label for="category">Gender</label>
            <select id="category" name="category">
                <option value="">Select gender</option>
                <option value="">Men</option>
                <option value="">Woman</option>
                <option value="shoes">Unisex</option>
                
                <!-- Add more categories as needed -->
            </select>
            </div>
        
        <div class="form-group">
            <label for="productName">Size</label>
            <input type="text" id="productName" name="productName" placeholder="Enter size...">
        </div>

        <!-- Price Range Fields -->
        <div class="form-group">
            <label>Price Range</label>
            <div class="price-range">
            <input type="number" id="minPrice" name="minPrice" placeholder="Min" min="0">
            <input type="number" id="maxPrice" name="maxPrice" placeholder="Max" min="0">
            </div>
        </div>
        
        
        <!-- Search Button -->
        <!--<button type="button" class="search-button" onclick="performSearch()">Search</button>-->
        <a href="shopS1.html">
            <button type="button" class="search-button">Search</button>
        </a>
        </form>
    </div>
  
  <!-- Result Display Section -->
    <div id="searchResults" style="margin-top: 20px; text-align: center;"></div>
    </div>



</body>
</html>