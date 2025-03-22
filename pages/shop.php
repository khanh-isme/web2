<?php
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <script src="../assets/js/shop.js" defer></script>
    <link rel="stylesheet" href="../assets/css/shop.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Filters</h2>
            <div class="filter-group">
                <label><input type="checkbox"> Boys</label><br>
                <label><input type="checkbox"> Girls</label>
            </div>
            
            <div class="filter-header" onclick="toggleSize()">Gender</div>
            <div class="filter-options" id="gender-options">
                <a href="shopF.html">Men</a>
                <a href="shopF.html">Women</a>
                <a href="shopF.html">Unisex</a>
            </div>

            <div class="filter-header-color" onclick="toggleSize1()">Category</div>
            <div class="filter-options" id="color-options">
                <a href="shopF.html">Sport</a>
                <a href="shopF.html">Fashion</a>
            </div>

            <div class="filter-header-collection" onclick="toggleSize2()">Collection</div>
            <div class="filter-options" id="collection-options">
                <a href="shopF.html">Winter</a>
                <a href="shopF.html">Summer</a>
                <a href="shopF.html">Spring</a>
            </div>
        </aside>

        <!-- Danh sách sản phẩm -->
        <main class="product-list">
            <div class="header">
                <h1>Shoes Vipro</h1>
            </div>

            <div class="products" id="product-for-shop"></div>
        </main>

       
    </div>


    

</body>
</html>

<?php
include '../includes/footer.php';       
?>
