<?php
require_once('../includes/config.php');
require_once ('../includes/get_product_info.php');
require_once ('../includes/funtion_product.php');


$productId = $_GET['id'] ?? null;

if ($productId) {
    $productResult = get_product_info($productId);

    if ($productResult['status'] === 'success') {
        $product = $productResult['product'];
        $sizes = get_product_sizes($productId);
    } else {
        die("Lỗi: " . $productResult['message']);
    } 
} else {
    die("Thiếu ID sản phẩm.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name'] ?? "Sản phẩm") ?></title>
    <link rel="stylesheet" href="/web2/assets/css/product.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
   
</head>
<body>

    <section class="product-section-cart">
        <div class="product-page">
            <div class="product-gallery">
                <div class="thumbnails">
                    <img src="<?= htmlspecialchars($product['image'] ?? '') ?>" alt="Thumbnail">
                    <img src="<?= htmlspecialchars($product['image'] ?? '') ?>" alt="Thumbnail">
                    <img src="<?= htmlspecialchars($product['image'] ?? '') ?>" alt="Thumbnail">
                    <img src="<?= htmlspecialchars($product['image'] ?? '') ?>" alt="Thumbnail">
                    
                </div>
                <div class="main-image">
                    <img src="<?= htmlspecialchars($product['image'] ?? '') ?>" alt="<?= htmlspecialchars($product['name'] ?? '') ?>">
                </div>
            </div>

            <div class="product-details">
                <h1><?= htmlspecialchars($product['name'] ?? 'Không có sản phẩm') ?></h1>
                <p class="price"><?= isset($product['price']) ? number_format($product['price'], 2) : '0.00' ?>VND</p> 
                <p class="description"><?= htmlspecialchars($product['description'] ?? 'Không có mô tả') ?></p>

                <div class="size-selection">
                    <h3>Select Size</h3>
                    <div class="sizes">
                        <?php if (!empty($sizes)): ?>
                            <?php foreach ($sizes as $size): ?>
                                <button 
                                    class="button" 
                                    onclick="selectButton(this)" 
                                    data-size="<?= htmlspecialchars($size['size']) ?>" 
                                    data-stock="<?= htmlspecialchars($size['stock']) ?>"
                                    data-size-id="<?= htmlspecialchars($size['id']) ?>"
                                    data-product-id="<?= htmlspecialchars($product['id']) ?>"
                                >
                                    <?= htmlspecialchars($size['size']) ?> (Còn <?= $size['stock'] ?>)
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: red;">Out of stock</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="quantity-selector">
                    <label for="quantity">Số lượng:</label>
                    <div class="quantity-controls">
                        <button type="button" onclick="changeQuantity(-1)">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1">
                        <button type="button" onclick="changeQuantity(1)">+</button>
                    </div>
                </div>
                 
                <a>
                    <button class="add-to-bag">Add to Cart</button>
                </a>
                <button class="favourite">Favourite ♥</button>

                <p class="notice">This product is excluded from site promotions and discounts.</p>
            </div>
        </div>
    </section>

    <script>
        function selectButton(button) {
            document.querySelectorAll('.button').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        }
    </script>

</body>
</html>
