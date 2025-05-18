<?php
require_once 'config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['items']) || !is_array($data['items'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$connection->begin_transaction();

try {
    foreach ($data['items'] as $item) {
        $stmt = $connection->prepare("UPDATE product_size SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $stmt->bind_param("iii", $item['quantity'], $item['product_size_id'], $item['quantity']);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Không đủ hàng cho sản phẩm có ID: " . $item['product_size_id']);
        }
    }

    $connection->commit();
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    $connection->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$connection->close();
