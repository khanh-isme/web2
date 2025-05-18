<?php
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

$username = trim($_POST['username']);
$fullname = trim($_POST['fullname']);
$email    = trim($_POST['email']);
$password = trim($_POST['password']);

// Kiểm tra trùng username hoặc email
$stmt = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => '<p><i class="fa-solid fa-triangle-exclamation red icon"></i>Username hoặc Email đã tồn tại!</p>'
    ]);
    exit;
}

// Mã hóa mật khẩu (nếu cần)

$stmt = $connection->prepare("INSERT INTO users (username, name, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $fullname, $email, $password);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => '<p><i class="fa-regular fa-circle-check green icon"></i>Đăng ký thành công!</p>'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => '<p><i class="fa-solid fa-bug red icon"></i>Lỗi đăng ký!</p>'
    ]);
}
$stmt->close();
