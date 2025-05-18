<?php
session_start();
require_once '../connect.php';
require_once 'getUser.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode([
        'status' => 'unauthorized',
        'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Chưa đăng nhập!</p>'
    ]);
    exit;
}

$user = getUser($_SESSION['user']['username']);

if ($user == null) {
    echo json_encode([
        'status' => 'user_not_found',
        'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Không tìm thấy tài khoản!</p>'
    ]);
    exit;
}

$oldPassword = $_POST['old-password'];
$newPassword = $_POST['new-password'];

if (!password_verify($oldPassword, $user['password'])) {
    echo json_encode([
        'status' => 'password_incorrect',
        'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Mật khẩu hiện tại không đúng!</p>'
    ]);
    exit;
}

$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$updateSql = "UPDATE admins SET password = ? WHERE username = ?";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bind_param("ss", $newHashedPassword, $user['username']);

if ($updateStmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => '<p><i class="fa-regular fa-circle-check green icon"></i>Đổi mật khẩu thành công!</p>'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Lỗi hệ thống!</p>'
    ]);
}

$updateStmt->close();
$conn->close();
