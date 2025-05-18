<?php
require_once("../../connect.php");
header('Content-Type: application/json');
session_start();

$sql = "SELECT id, username, fullname, status, role FROM admins where status = 'active' or status = 'inactive' ORDER BY id ASC";
$result = $conn->query($sql);

$admins = [];
$stt = 1;

while ($row = $result->fetch_assoc()) {
    $row['stt'] = $stt++;
    $row['isCurrentAdmin'] = $row['username']===$_SESSION['user']['username'];
    $admins[] = $row;
}

echo json_encode($admins);

$conn->close();
?>