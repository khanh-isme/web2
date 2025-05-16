<?php
require_once("../../connect.php");
header('Content-Type: application/json');

$result = $conn->query("SELECT id, role_name FROM roles");
$roles = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = $row['role_name'];
}
echo json_encode($roles);
$conn->close();
?>