<?php
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

function get_product_size_info($product_size_id) {
    global $connection;

    if (!is_numeric($product_size_id) || $product_size_id <= 0) {
        return [
            'status' => 'error',
            'message' => 'product_size_id không hợp lệ.'
        ];
    }

    $sql = "SELECT id, product_id, size, stock FROM product_size WHERE id = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $product_size_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return [
                'status' => 'success',
                'product_size' => $row
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Không tìm thấy thông tin product_size.'
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

// Ví dụ sử dụng với query string: ?id=5
if (isset($_GET['id'])) {
    echo json_encode(get_product_size_info($_GET['id']));
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu tham số id.'
    ]);
}

?>

