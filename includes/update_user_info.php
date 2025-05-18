<?php
header('Content-Type: application/json');

// Kết nối CSDL từ config.php
require_once 'config.php';

// Kiểm tra kết nối
if ($connection->connect_error) {
    echo json_encode(["status" => "error", "message" => "Kết nối CSDL thất bại: " . $connection->connect_error]);
    exit;
}

// Lấy dữ liệu JSON từ frontend
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu đầu vào
if (!$data || !isset($data['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu người dùng."]);
    exit;
}

// Lấy dữ liệu người dùng
$user_id = intval($data['user_id']);
$name = $connection->real_escape_string($data['name']);
$email = $connection->real_escape_string($data['email']);
$phone = $connection->real_escape_string($data['phone']);
$address = $connection->real_escape_string($data['address']);

// Chuẩn bị câu lệnh UPDATE
$sql = "UPDATE users SET name=?, email=?, phone=?, address=? WHERE id=?";

$stmt = $connection->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Chuẩn bị truy vấn thất bại: " . $connection->error]);
    exit;
}

// Gán tham số và thực thi
$stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Thông tin đã được cập nhật thành công."]);
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi cập nhật: " . $stmt->error]);
}

$stmt->close();
$connection->close();
?>
