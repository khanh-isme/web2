<?php
// Đảm bảo file chỉ được include một lần
if (!defined('HEADER_INCLUDED')) {
    define('HEADER_INCLUDED', true);
}
?>

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

<!-- Header Style -->
<link rel="stylesheet" href="/web2/assets/css/header.css">

<!-- Header Script -->
<script src="/web2/assets/js/header.js" defer></script>

<header>
    <a href="/web2/index.php" class="logo">
        <img src="/web2/assets/images/Adidas-Logo.png" alt="Logo">
    </a>

    <nav>
        <ul class="nav-menu">
            <li><a href="#" class="nav-link" data-page="home.php">Home</a></li>
            <li><a href="#" class="nav-link" data-page="shop.php">Shop</a></li>
            <li><a href="#" class="nav-link" data-page="about.php">About</a></li>
            <li><a href="#" class="nav-link" data-page="sale.php">Sale</a></li>
        </ul>
    </nav>

    <div class="nav-icon">
        <div class="search-icon">
            <i class='bx bx-search' onclick="toggleSearch()"></i>
        </div>
        <a href="/web2/pages/login.php"><i class='bx bx-user'></i></a>
        <div class="cart-icon" onclick="toggleCartPopup()">
            <i class='bx bx-cart' id="cart-icon"></i>
        </div>
        <div class="bx bx-menu" id="menu-icon"></div>
    </div>
</header>

<!-- Cart Popup -->
<div class="cart-popup" id="cartPopup">
    <!-- Mỗi item có thể được tạo bằng JS từ dữ liệu giỏ hàng -->
    <div class="cart-item">
        <img src="/web2/images/13.png" alt="Product Image">
        <div class="cart-item-details">
            <div class="cart-item-name">T-Shirt 14A</div>
            <div class="cart-item-price">$50.05 x1</div>
        </div>
        <div class="cart-item-delete" onclick="removeItem()">
            <i class='bx bxs-trash-alt'></i>
        </div>
    </div>

    <a href="/web2/pages/bag.php">
        <p>See more...</p>
    </a>

    <div class="cart-total">Total: $100.10</div>
    <a href="/web2/pages/bag.php">
        <button class="checkout-btn">CHECK YOUR BAG</button>
    </a>
</div>

<!-- Search Popup -->
<div class="search-container" id="searchPopup">
    <h2>Advanced Product Search</h2>
    <form id="searchForm">
        <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" id="productName" name="productName" placeholder="Enter product name...">
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category">
                <option value="">Select category</option>
                <option value="sneakers">Sneakers</option>
                <option value="boots">Boots</option>
                <option value="sandals">Sandals</option>
                <option value="loafers">Loafers</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="">Select gender</option>
                <option value="nam">Men</option>
                <option value="nu">Women</option>
                <option value="unisex">Unisex</option>
            </select>
        </div>
        <div class="form-group">
            <label for="size">Size</label>
            <input type="text" id="size" name="size" placeholder="Enter size...">
        </div>
        <div class="form-group">
            <label>Price Range</label>
            <div class="price-range">
                <input type="number" id="minPrice" name="minPrice" placeholder="Min" min="0">
                <input type="number" id="maxPrice" name="maxPrice" placeholder="Max" min="0">
            </div>
        </div>
        <button type="submit" class="search-button">Search</button>
    </form>
</div>
