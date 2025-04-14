<?php 
// Kết nối database
$host = "127.0.0.1:3307";
$user = "root";
$password = "";
$database = "shoe";

$connection = new mysqli($host, $user, $password, $database);
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Kiểm tra kết nối database
if ($connection->connect_error) {
    die(json_encode(["error" => "Lỗi: Không thể kết nối đến cơ sở dữ liệu."]));
}

// Kiểm tra tham số ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die(json_encode(["error" => "Lỗi: Tham số ID không hợp lệ."]));
}

$product_id = intval($_GET['id']);

$response = [
    'product' => [],
    'sizes' => []
];

// Truy vấn sản phẩm
$productQuery = "SELECT * FROM products WHERE id = ?";
$stmt = $connection->prepare($productQuery);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$productResult = $stmt->get_result();

if ($productResult->num_rows > 0) {
    $response['product'] = $productResult->fetch_assoc();
}

// Truy vấn size sản phẩm
$productSizesQuery = "SELECT size FROM product_size WHERE product_id = ?";
$stmt = $connection->prepare($productSizesQuery);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$productSizesResult = $stmt->get_result();

if ($productSizesResult->num_rows > 0) {
    while ($row = $productSizesResult->fetch_assoc()) {
        $response['sizes'][] = $row['size'];
    }
}

$product = $response['product'];
$sizes = $response['sizes'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name'] ?? "Sản phẩm") ?></title>
    <link rel="stylesheet" href="/web2/assets/css/product.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="/web2/assets/js/product.js" defer></script>
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
                <p class="price">$<?= isset($product['price']) ? number_format($product['price'], 2) : '0.00' ?></p>
                <p class="description"><?= htmlspecialchars($product['description'] ?? 'Không có mô tả') ?></p>

                <div class="size-selection">
                    <h3>Select Size</h3>
                    <div class="sizes">
                        <?php if (!empty($sizes)): ?>
                            <?php foreach ($sizes as $size): ?>
                                <button class="button" onclick="selectButton(this)">
                                    <?= htmlspecialchars($size) ?>
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: red;">Out of stock</p>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="bag.php?id=<?= $product_id ?>&name=<?= urlencode($product['name'] ?? '') ?>&price=<?= $product['price'] ?? 0 ?>&image=<?= urlencode($product['image'] ?? '') ?>">
                    <button class="add-to-bag">Add to Bag</button>
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
