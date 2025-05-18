<?php
// filepath: c:\xampp\htdocs\web2-cuaAnhSondz\admin\includes\deleteEmployee.php
require '../../connect.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("UPDATE admins SET status = 'deleted' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đã xóa nhân viên (status = deleted)!']);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu hoặc sai id!']);
}
$conn->close();
?>