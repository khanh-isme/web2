<?php
require_once '../../connect.php';

header('Content-Type: application/json');

try {
    // Truy vấn danh sách categories
    $sql = "SELECT id, name FROM categories";
    $result = $conn->query($sql);

    $categories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    // Trả về danh sách categories dưới dạng JSON
    echo json_encode(['categories' => $categories]);
} catch (Exception $e) {
    // Trả về lỗi nếu có
    echo json_encode(['error' => 'Lỗi khi tải danh sách categories: ' . $e->getMessage()]);
}

$conn->close();
?>