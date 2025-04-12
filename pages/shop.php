<?php
if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    // Lấy dữ liệu từ query string
    $productName = isset($_GET['name']) ? trim($_GET['name']) : '';
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    $collection = isset($_GET['collection']) ? trim($_GET['collection']) : '';
    $gender = isset($_GET['gender']) ? trim($_GET['gender']) : '';
    $size = isset($_GET['size']) ? trim($_GET['size']) : '';
    $minPrice = isset($_GET['minPrice']) ? (float) $_GET['minPrice'] : 0;
    $maxPrice = isset($_GET['maxPrice']) ? (float) $_GET['maxPrice'] : 0;
}


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
    <input type="hidden" id="productName" value="<?= htmlspecialchars($productName) ?>">
    <input type="hidden" id="category" value="<?= htmlspecialchars($category) ?>">
    <input type="hidden" id="collection" value="<?= htmlspecialchars($collection) ?>">
    <input type="hidden" id="gender" value="<?= htmlspecialchars($gender) ?>">
    <input type="hidden" id="size" value="<?= htmlspecialchars($size) ?>">
    <input type="hidden" id="minPrice" value="<?= htmlspecialchars($minPrice) ?>">
    <input type="hidden" id="maxPrice" value="<?= htmlspecialchars($maxPrice) ?>">



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