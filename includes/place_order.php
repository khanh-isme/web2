<?php
    require_once 'config.php';
    header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$sql = "INSERT INTO orders (customer_id, order_date, total_amount, status, 
        shipping_name, shipping_phone, shipping_address)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $connection->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi truy vấn.']);
    exit;
}

$stmt->bind_param(
    "isdssss",
    $data['customer_id'],
    $data['order_date'],
    $data['total_amount'],
    $data['status'],
    $data['shipping_name'],
    $data['shipping_phone'],
    $data['shipping_address']
);
if ($stmt->execute()) {
    $order_id = $stmt->insert_id; // Lấy ID vừa insert
    echo json_encode(['status' => 'success', 'order_id' => $order_id]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không thể lưu đơn hàng.']);
}

$stmt->close();
$connection->close();
