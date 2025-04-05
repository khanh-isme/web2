<?php
$host = "127.0.0.1:3307";
$user = "root"; // Thay bằng user của bạn
$password = ""; // Thay bằng mật khẩu của bạn
$database = "shoe"; // Thay bằng tên database

$connection = new mysqli($host, $user, $password, $database);

error_reporting(E_ALL);
ini_set('display_errors', 1);


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 7;
$offset = ($page - 1) * $limit;

$response = [
    'products' => [],
    'totalPages' => 0
];

try {
    // Lấy tổng số sản phẩm
    $totalProductsQuery = "SELECT COUNT(*) as total FROM products";
    $totalProductsResult = $connection->query($totalProductsQuery);
    $totalProducts = $totalProductsResult->fetch_assoc()['total'];

    // Tính tổng số trang
    $response['totalPages'] = ceil($totalProducts / $limit);

    // Lấy danh sách sản phẩm theo phân trang
    $productsQuery = "SELECT * FROM products LIMIT $limit OFFSET $offset";
    $productsResult = $connection->query($productsQuery);

    if ($productsResult->num_rows > 0) {
        while ($row = $productsResult->fetch_assoc()) {
            $response['products'][] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'] ?? '',
            'price' => number_format($row['price'], 0, ',', '.') . ' VND',
            'image' => $row['image'] ?? 'default.jpg',
            'category_id' => $row['category_id'],
            'collection_id' => $row['collection_id'],
            'gender' => $row['gender'],
            'created_at' => date('c', strtotime($row['created_at'])) // Định dạng ISO 8601
            ];
        }
    }

    // Trả về dữ liệu JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Lỗi khi tải sản phẩm: ' . $e->getMessage()]);
}
?>