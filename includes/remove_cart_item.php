<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user']['id'] ?? null;
$product_size_id = $data['product_size_id'] ?? null;

if (!$user_id || !$product_size_id) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu']);
    exit;
}

$stmt = $connection->prepare("DELETE FROM cart WHERE user_id = ? AND product_size_id = ?");
$stmt->bind_param("ii", $user_id, $product_size_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xóa sản phẩm']);
}
exit;
?>
