<?php
// filepath: c:\xampp\htdocs\web2-cuaAnhSondz\admin\includes\updateEmployee.php
require '../../connect.php';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$password = $_POST['password'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$role     = $_POST['role'] ?? '';
$status   = $_POST['status'] ?? '';

if ($id <= 0 || $role === '' || $status === '') {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$params = [];
$types = '';
$sql = "UPDATE admins SET fullname = ?, role = ?, status = ?";
$params = [$fullname, $role, $status];
$types = 'sss';

if ($password !== '') {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql .= ", password = ?";
    $params[] = $hashedPassword;
    $types .= 's';
}

$sql .= " WHERE id = ?";
$params[] = $id;
$types .= 'i';

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Cập nhật nhân viên thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}
$stmt->close();
$conn->close();
?>