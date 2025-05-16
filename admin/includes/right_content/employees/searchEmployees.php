<?php

require_once("../../connect.php");
header('Content-Type: application/json');


$data = $_POST;
if (empty($data)) {
    $data = json_decode(file_get_contents('php://input'), true);
}
$username = isset($data['username']) ? trim($data['username']) : '';
$fullname = isset($data['fullname']) ? trim($data['fullname']) : '';
$status   = isset($data['status']) ? trim($data['status']) : '';
$role     = isset($data['role']) ? trim($data['role']) : '';

$sql = "SELECT * FROM admins WHERE (status = 'active' OR status = 'inactive')"; // Lọc theo trạng thái
$params = [];
$types = "";

if ($username !== "") {
    $sql .= " AND username LIKE ?";
    $params[] = "%$username%";
    $types .= "s";
}
if ($fullname !== "") {
    $sql .= " AND fullname LIKE ?";
    $params[] = "%$fullname%";
    $types .= "s";
}
if ($status !== "") {
    $sql .= " AND status = ?";
    $params[] = $status;
    $types .= "s";
}
if ($role !== "") {
    $sql .= " AND role = ?";
    $params[] = $role;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$employees = [];
$stt = 1;
while ($row = $result->fetch_assoc()) {
    $row['stt'] = $stt++;
    $employees[] = $row;
}

echo json_encode(['employees' => $employees]);

$stmt->close();
$conn->close();
