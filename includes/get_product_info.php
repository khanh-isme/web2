<?php
require_once 'config.php'; // Kết nối CSDL
header('Content-Type: application/json; charset=utf-8');

function get_product_info($product_id) {
    global $connection;

    if (!is_numeric($product_id) || $product_id <= 0) {
        return [
            'status' => 'error',
            'message' => 'product_id không hợp lệ.'
        ];
    }

    $sql = "SELECT id, name, category_id, price, description, image, created_at, gender 
            FROM products 
            WHERE id = ? AND deleted = 0";

    $stmt = $connection->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return [
                'status' => 'success',
                'product' => $row
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Không tìm thấy sản phẩm.'
            ];
        }

        $stmt->close();
    } else {
        return [
            'status' => 'error',
            'message' => 'Lỗi truy vấn CSDL.'
        ];
    }
}

// Gọi thử hàm bằng query string: ?id=3
if (isset($_GET['id'])) {
    echo json_encode(get_product_info($_GET['id']));
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu tham số id.'
    ]);
}

?>
