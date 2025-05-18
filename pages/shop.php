<?php
if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    // Lấy dữ liệu từ query string
    $productName = isset($_GET['name']) ? trim($_GET['name']) : '';
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
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
    <link rel="stylesheet" href="/web2/assets/css/shop.css">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <input type="hidden" id="productName" value="<?= htmlspecialchars($productName) ?>">
    <input type="hidden" id="category" value="<?= htmlspecialchars($category) ?>">
    <input type="hidden" id="gender" value="<?= htmlspecialchars($gender) ?>">
    <input type="hidden" id="size" value="<?= htmlspecialchars($size) ?>">
    <input type="hidden" id="minPrice" value="<?= htmlspecialchars($minPrice) ?>">
    <input type="hidden" id="maxPrice" value="<?= htmlspecialchars($maxPrice) ?>">



    <div class="container">
        <aside class="sidebar">
        <h2>Filters</h2>

        <!-- Gender Filter -->
        <div class="filter-header">Gender</div>
            <div class="filter-options" id="gender-options">
                <label><input type="radio" name="gender" value="nam"> Men</label><br>
                <label><input type="radio" name="gender" value="nu"> Women</label><br>
                <label><input type="radio" name="gender" value="unisex"> Unisex</label>
            </div>


        <!-- Category Filter -->
        <div class="filter-header">Category</div>
        <div class="filter-options" id="category-options">
            <label><input type="radio" name="category" value="sneakers"> Sneakers</label><br>
            <label><input type="radio" name="category" value="boots"> Boots</label><br>
            <label><input type="radio" name="category" value="sandals"> Sandals</label><br>
            <label><input type="radio" name="category" value="loafers"> Loafers</label><br>
            <label><input type="radio" name="category" value="athletic"> Athletic</label>
        </div>

        
        </aside>


        <!-- Danh sách sản phẩm -->
        <main class="product-list">
            <div class="header">
                <h1>Shoes </h1>
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