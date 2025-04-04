<?php
    
$searchName = isset($_GET['name']) ? trim($_GET['name']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

    <script src="/web2/assets/js/shop.js" defer></script>
    <link rel="stylesheet" href="/web2/assets/css/shop.css">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <input type="hidden" id="productName" value="<?= htmlspecialchars($searchName) ?>">


    <div class="container">
        <aside class="sidebar">
            <h2>Filters</h2>

            <div class="filter-header" onclick="toggleSize()">Gender</div>
            <div class="filter-options" id="gender-options">
                <a class="filter-options-element" onclick="filterMen()">Men</a>
                <a class="filter-options-element" onclick="filterWomen()">Women</a>
                <a class="filter-options-element" onclick="filterUnisex()">Unisex</a>
            </div>

            <div class="filter-header-color" onclick="toggleSize1()">Category</div>
            <div class="filter-options" id="color-options">
                <a class="filter-options-element">Sneakers</a>
                <a class="filter-options-element">Boots</a>
                <a class="filter-options-element">Sandals</a>
                <a class="filter-options-element">Loafers</a>
                <a class="filter-options-element">Athletic</a>

            </div>

            <div class="filter-header-collection" onclick="toggleSize2()">Collection</div>
            <div class="filter-options" id="collection-options">
                <a class="filter-options-element">Summer Collection
                </a>
                <a class="filter-options-element">Winter Collection</a>
                <a class="filter-options-element">Limited Edition</a>
                <a class="filter-options-element">Running Shoes</a>
                <a class="filter-options-element">Casual Style</a>
            </div>
        </aside>

        <!-- Danh sách sản phẩm -->
        <main class="product-list">
            <div class="header">
                <h1>Shoes Vipro</h1>
            </div>

            <div class="products" id="product-for-shop"></div>


            <div class="pagination">
                <button id="prevBtn" onclick="changePage(-1)">Previous</button>
                <span>Page <span id="currentPage">1</span></span>
                <button id="nextBtn" onclick="changePage(1)">Next</button>
            </div>

        </main>

    </div>
</body>

</html>

<?php
include(__DIR__ . "/../includes/footer.php");
?>