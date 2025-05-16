<?php
require_once("../../connect.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$id = isset($data['id']) ? intval($data['id']) : 0;
$permissions = isset($data['permissions']) && is_array($data['permissions']) ? $data['permissions'] : [];

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Thiếu hoặc sai id nhân viên!']);
    exit;
}


$stmt = $conn->prepare("DELETE FROM admin_permissions WHERE admin_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

if (!empty($permissions)) {
    $stmt = $conn->prepare("INSERT INTO admin_permissions (admin_id, perm_id) VALUES (?, ?)");
    foreach ($permissions as $perm_id) {
        $perm_id = intval($perm_id);
        $stmt->bind_param("ii", $id, $perm_id);
        $stmt->execute();
    }
    $stmt->close();
}

echo json_encode(['success' => true, 'message' => 'Cập nhật quyền thành công!']);
$conn->close();
?>