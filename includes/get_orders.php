<?php
require_once 'config.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

function getOrdersOfCurrentUser() {
    global $connection;

    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        return [
            'status' => 'error',
            'message' => 'Bạn chưa đăng nhập hoặc thông tin không hợp lệ.'
        ];
    }

    $customer_id = $_SESSION['user']['id'];

    $sql = "SELECT order_id, customer_id, order_date, total_amount, status, shipping_name, shipping_phone, shipping_address 
            FROM orders 
            WHERE customer_id = ?";

    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        return [
            'status' => 'error',
            'message' => 'Lỗi truy vấn CSDL.'
        ];
    }

    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    $stmt->close();

    return [
        'status' => 'success',
        'orders' => $orders
    ];
}

echo json_encode(getOrdersOfCurrentUser());
$connection->close();
?>
