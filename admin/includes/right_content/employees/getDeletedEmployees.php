<?php
// filepath: c:\xampp\htdocs\web2-cuaAnhSondz\admin\includes\getDeletedEmployees.php
require_once("../../connect.php");
header('Content-Type: application/json');

$result = $conn->query("SELECT id, username FROM admins WHERE status = 'deleted'");
$deleted = [];
$stt = 1;
while ($row = $result->fetch_assoc()) {
    $row['stt'] = $stt++;
    $deleted[] = $row;
}
echo json_encode($deleted);
$conn->close();
?>