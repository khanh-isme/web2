<?php
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

function get_cart_items($user_id) {
    global $connection;

    if (!is_numeric($user_id) || $user_id <= 0) {
        return [
            'status' => 'error',
            'message' => 'user_id không hợp lệ.'
        ];
    }

    $sql = "SELECT id, user_id, product_id, product_size_id, quantity, added_at 
            FROM cart 
            WHERE user_id = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        return [
            'status' => 'success',
            'cart_items' => $items
        ];

        $stmt->close();
    } else {
        return [
            'status' => 'error',
            'message' => 'Lỗi truy vấn CSDL.'
        ];
    }
}

if (isset($_GET['user_id'])) {
    echo json_encode(get_cart_items($_GET['user_id']));
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu tham số user_id.'
    ]);
}

$connection->close();
?>
