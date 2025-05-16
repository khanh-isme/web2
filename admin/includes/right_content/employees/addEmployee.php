<?php

require '../../connect.php';

header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$role     = $_POST['role'] ?? '';
$status   = "active";

if ($username === '' || $password === '' || $role === ''|| $fullname === '') {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}


$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, password, fullname, status, role) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $username, $hashedPassword, $fullname, $status, $role);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}
$stmt->close();
$conn->close();
?>