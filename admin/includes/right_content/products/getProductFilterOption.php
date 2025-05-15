<?php
require_once '../../connect.php';
require_once 'Category.php';

header('Content-Type: application/json');

// Khởi tạo đối tượng Category và Collection
$categoryObj = new Category($conn);

// Lấy danh sách category và collection
$categories = $categoryObj->getAllCategories();
$collections = $collectionObj->getAllCollections();

$query = "SELECT DISTINCT gender FROM products";
$result = $conn->query($query);
$genders = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $genders[] = $row['gender'];
    }
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode([
    'gender' => $genders,
    'categories' => $categories,
]);

// Đóng kết nối
$conn->close();
