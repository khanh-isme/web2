<?php
require_once 'config.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

function getLatestShippingAddress() {
    global $connection;

    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        return [
            'status' => 'error',
            'message' => 'Bạn chưa đăng nhập hoặc thông tin người dùng không hợp lệ.'
        ];
    }

    $customer_id = $_SESSION['user']['id'];

    $sql = "SELECT shipping_name, shipping_phone, shipping_address 
            FROM orders 
            WHERE customer_id = ? 
            ORDER BY order_date DESC 
            LIMIT 1";

    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        return [
            'status' => 'error',
            'message' => 'Lỗi truy vấn cơ sở dữ liệu.'
        ];
    }

    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $shippingInfo = $result->fetch_assoc();

    $stmt->close();

    if ($shippingInfo) {
        return [
            'status' => 'success',
            'shipping' => $shippingInfo
        ];
    } else {
        return [
            'status' => 'empty',
            'message' => 'Không tìm thấy thông tin giao hàng nào.'
        ];
    }
}

echo json_encode(getLatestShippingAddress());
$connection->close();
?>
