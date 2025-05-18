<?php
// filepath: c:\xampp\htdocs\web2-cuaAnhSondz\admin\includes\getEmployeeById.php
require '../../connect.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, username, fullname, role, status FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'employee' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy nhân viên!']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu hoặc sai id!']);
}
$conn->close();
?>