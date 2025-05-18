<?php
require_once 'config.php'; // Kết nối CSDL
header('Content-Type: application/json');

// Đọc dữ liệu JSON từ request
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu hợp lệ
if (!isset($data['order_details']) || !is_array($data['order_details'])) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu chi tiết đơn hàng']);
    exit;
}

$orderDetails = $data['order_details'];

try {
    $connection->begin_transaction();

    $stmt = $connection->prepare("INSERT INTO order_details (order_id, product_size_id, quantity, price) VALUES (?, ?, ?, ?)");

    foreach ($orderDetails as $detail) {
        $order_id = $detail['order_id'];
        $product_size_id = $detail['product_size_id'];
        $quantity = $detail['quantity'];
        $price = $detail['price'];

        $stmt->bind_param("iiid", $order_id, $product_size_id, $quantity, $price);
        $stmt->execute();
    }

    $connection->commit();
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi khi lưu order_details: ' . $e->getMessage()
    ]);
}
?>
