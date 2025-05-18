<?php
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

function get_order_details($order_id) {
    global $connection;

    if (!is_numeric($order_id) || $order_id <= 0) {
        return [
            'status' => 'error',
            'message' => 'order_id không hợp lệ.'
        ];
    }

    $sql = "SELECT order_detail_id, order_id, product_size_id, quantity, price 
            FROM order_details 
            WHERE order_id = ?";

    $stmt = $connection->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $details = [];
        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }

        $stmt->close();

        return [
            'status' => 'success',
            'order_id' => $order_id,
            'details' => $details
        ];
    } else {
        return [
            'status' => 'error',
            'message' => 'Lỗi truy vấn CSDL.'
        ];
    }
}

// Ví dụ: Lấy từ query string ?order_id=5
if (isset($_GET['order_id'])) {
    echo json_encode(get_order_details($_GET['order_id']));
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu order_id.'
    ]);
}

$connection->close();
?>
