<?php
require_once '../../connect.php';
require_once 'Product.php';

header('Content-Type: application/json');

$product = new Product($conn);
$products = $product->getAllProducts();

echo json_encode($products);
?>