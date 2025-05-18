<?php
require_once 'config.php'; // File chứa thông tin kết nối CSDL
session_start();
header('Content-Type: application/json');

// Kiểm tra nếu có username được truyền vào từ URL
if (!isset($_SESSION['user'])) {
    echo json_encode([
        'status' => 'error',
        'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>bạn chưa đăng nhập</p>'
    ]);
    exit();
}   


$username = $_SESSION['user']['username'];
// Truy vấn thông tin người dùng theo username
$sql = "SELECT username, name, email, phone, address, status FROM users WHERE username = ?";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        echo json_encode([
            'status' => 'success',
            'user' => $user
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Không tìm thấy người dùng.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi truy vấn cơ sở dữ liệu.'
    ]);
}

$connection->close();
?>
