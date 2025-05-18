<?php
include "config.php";
if ($connection->connect_error) {
    die(json_encode(["error" => "Kết nối database thất bại: " . $connection->connect_error]));
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");



// Nhận tham số từ request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 7;
$gender = isset($_GET['gender']) ? trim($_GET['gender']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$name = isset($_GET['name']) ? trim($_GET['name']) : '';
$minPrice = isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : 0;

if ($page < 1) $page = 1;
if ($limit < 1) $limit = 7;





$offset = ($page - 1) * $limit;
$response = [
    'products' => [],
    'totalPages' => 0
];

// Xây dựng điều kiện lọc với Prepared Statements
$whereConditions = [];
$params = [];
$types = "";

// Lọc theo giới tính
if (!empty($gender)) {
    $whereConditions[] = "gender = ?";
    $params[] = $gender;
    $types .= "s";
}

// Lọc theo danh mục
if (!empty($category)) {
    $whereConditions[] = "category_id = (SELECT id FROM categories WHERE name = ?)";
    $params[] = $category;
    $types .= "s";
}



// Lọc theo tên sản phẩm (tìm kiếm)
if (!empty($name)) {
    $whereConditions[] = "name LIKE ?";
    $params[] = "%$name%";
    $types .= "s";
}

// Lọc theo giá tối thiểu
if ($minPrice > 0) {
    $whereConditions[] = "price >= ?";
    $params[] = $minPrice;
    $types .= "i";
}

// Tạo câu lệnh WHERE
$whereClause = count($whereConditions) > 0 ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Câu lệnh SQL lấy tổng số sản phẩm
$totalProductsQuery = "SELECT COUNT(*) as total FROM products $whereClause";
$totalStmt = $connection->prepare($totalProductsQuery);
if (!empty($params)) {
    $totalStmt->bind_param($types, ...$params);
}
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalProducts = $totalResult->fetch_assoc()['total'];
$response['totalPages'] = ceil($totalProducts / $limit);

// Câu lệnh SQL lấy danh sách sản phẩm
$productsQuery = "SELECT * FROM products $whereClause LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$productStmt = $connection->prepare($productsQuery);
$productStmt->bind_param($types, ...$params);
$productStmt->execute();
$productsResult = $productStmt->get_result();

// Lấy danh sách sản phẩm
while ($row = $productsResult->fetch_assoc()) {
    $response['products'][] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'description' => $row['description'] ?? '',
        'price' => number_format($row['price'], 0, ',', '.') . ' VND',
        'image' => $row['image'] ?? 'default.jpg',
        'category_id' => $row['category_id'],
        'gender' => $row['gender'],
        'created_at' => date('c', strtotime($row['created_at'])) // Định dạng ISO 8601
    ];
}

echo json_encode($response);
?>
