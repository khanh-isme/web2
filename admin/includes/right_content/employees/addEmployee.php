<?php

require '../../connect.php';

header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$role     = $_POST['role'] ?? '';
$status   = "active";

if ($username === '' || $password === '' || $role === '' || $fullname === '') {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin.']);
    exit;
}

// Kiểm tra username đã tồn tại chưa
$checkSql = "SELECT id FROM admins WHERE username = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $username);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'username_duplicated']);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// Mã hóa mật khẩu
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Chèn dữ liệu mới
$conn->begin_transaction();
$insertSql = "INSERT INTO admins (username, password, fullname, status, role) VALUES (?, ?, ?, ?, ?)";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param("sssss", $username, $hashedPassword, $fullname, $status, $role);

$rolesPermsStmt = $conn->prepare('SELECT p.id
        FROM permissions p
        JOIN roles_permissions rp ON p.id = rp.perm_id
        JOIN roles on rp.role_id = roles.id
        WHERE roles.role_name = ?;
        ');
$rolesPermsStmt->bind_param("s",$role);
$rolesPermsStmt->execute();
$rolesPermsStmt->bind_result($perm);
$rolesPerms=[];
while($rolesPermsStmt->fetch())
{
    $rolesPerms[]=$perm;
}
$rolesPermsStmt->close();

if ($insertStmt->execute()) {
    $insertStmt->close();
    $IDstmt=$conn->prepare('select id from admins where username = ?');
    $IDstmt->bind_param("s",$username);
    $IDstmt->execute();
    $IDstmt->bind_result($adminID);
    $IDstmt->fetch();
    $IDstmt->close();
    if (count($rolesPerms) > 0) {
        $values = array_map(fn($perm) => "($adminID, $perm)", $rolesPerms);
        $sql="INSERT INTO admin_permissions (admin_id, perm_id) VALUES " . implode(', ', $values) . ";";
        
        if ($conn->query($sql))
        {
            echo json_encode(['success' => true]);
            $conn->commit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm nhân viên: ' . $conn->error]);
            $conn->rollback();
            exit();
        }
    }
    else
    {
        echo json_encode(['success' => true]);
        $conn->commit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm nhân viên: ' . $conn->error]);
    $conn->rollback();
    exit();
}

$conn->close();