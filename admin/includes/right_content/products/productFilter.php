<?php
require_once '../../connect.php';
require_once 'Product.php';

header('Content-Type: application/json');

$product = new Product($conn);

// Lấy dữ liệu từ request
$keyword = $_POST['keyword'] ?? '';
$category_id = $_POST['category_id'] ?? '';
$gender = $_POST['gender'] ?? '';
$price_min = $_POST['price_min'] ?? 0;
$price_max = $_POST['price_max'] ?? PHP_INT_MAX;

try {
    // Truy vấn sản phẩm theo bộ lọc
    $sql = "
        SELECT 
            p.id, 
            p.name, 
            p.price, 
            p.image, 
            p.gender, 
            p.description, 
            c.name AS category_name, 
            SUM(ps.stock) AS total_stock
        FROM 
            products p
        LEFT JOIN 
            categories c ON p.category_id = c.id
        LEFT JOIN 
            product_size ps ON p.id = ps.product_id
        WHERE 
            p.deleted = 0
    ";

    // Thêm điều kiện lọc
    $conditions = [];
    $params = [];
    $types = '';

    if (!empty($keyword)) {
        $conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
        $types .= 'ss';
    }

    if (!empty($category_id)) {
        $conditions[] = "p.category_id = ?";
        $params[] = $category_id;
        $types .= 'i';
    }

    if (!empty($gender)) {
        $conditions[] = "p.gender = ?";
        $params[] = $gender;
        $types .= 's';
    }

    if (!empty($price_min)) {
        $conditions[] = "p.price >= ?";
        $params[] = $price_min;
        $types .= 'd';
    }

    if (!empty($price_max)) {
        $conditions[] = "p.price <= ?";
        $params[] = $price_max;
        $types .= 'd';
    }

    if (!empty($conditions)) {
        $sql .= " AND " . implode(' AND ', $conditions);
    }

    $sql .= " GROUP BY p.id";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error filtering products: ' . $e->getMessage()]);
}
?>