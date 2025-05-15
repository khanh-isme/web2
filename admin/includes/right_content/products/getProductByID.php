<?php
require_once '../../connect.php';
require_once 'Product.php';

header('Content-Type: application/json');

$product = new Product($conn);

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'Product ID is required']);
    exit;
}

try {
    $productData = $product->getProductById($id);

    if (!$productData) {
        echo json_encode(['error' => 'Product not found']);
        exit;
    }

    $sql = "SELECT size, stock FROM product_size WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sizes = [];
    while ($row = $result->fetch_assoc()) {
        $sizes[] = $row;
    }

    $productData['sizes'] = $sizes;

    echo json_encode($productData);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching product: ' . $e->getMessage()]);
}
