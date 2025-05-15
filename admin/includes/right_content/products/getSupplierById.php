<?php
require_once '../../connect.php';

if (isset($_GET['id'])) {
    $supplierId = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = ?");
    $stmt->bind_param("i", $supplierId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($supplier = $result->fetch_assoc()) {
        echo json_encode($supplier);
    } else {
        echo json_encode(null);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
