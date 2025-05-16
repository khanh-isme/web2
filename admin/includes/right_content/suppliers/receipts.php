<?php
require_once '../../connect.php';

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'receipts') {
    $query = "SELECT r.id, r.receipt_date, r.total_amount, r.discount_percent, s.name AS supplier_name 
              FROM receipts r 
              JOIN suppliers s ON r.supplier_id = s.id 
              ORDER BY r.receipt_date DESC";
    $result = $conn->query($query);

    $receipts = [];
    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $receipts[] = [
            'id' => $row['id'],
            'receipt_date' => $row['receipt_date'],
            'total_amount' => (float)$row['total_amount'],
            'supplier_name' => $row['supplier_name']
        ];
        $total += (float)$row['total_amount'];
    }

    echo json_encode(['success' => true, 'results' => $receipts, 'total_cost' => number_format($total, 2)]);
} elseif ($action === 'receipt_details' && isset($_GET['receipt_id'])) {
    
    $receipt_id = (int)$_GET['receipt_id'];

    $query = "SELECT r.*, s.name AS supplier_name 
              FROM receipts r 
              JOIN suppliers s ON r.supplier_id = s.id 
              WHERE r.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $receipt_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $receipt = $result->fetch_assoc();

    if (!$receipt) {
        echo json_encode(['success' => false, 'error' => 'Phiếu nhập không tồn tại']);
        exit;
    }

    $query = "SELECT rd.*, ps.size, p.id AS product_id, p.name AS product_name 
              FROM receipt_details rd 
              JOIN product_size ps ON rd.product_size_id = ps.id 
              JOIN products p ON ps.product_id = p.id 
              WHERE rd.receipt_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $receipt_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $details = [];
    while ($row = $result->fetch_assoc()) {
        $details[] = [
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'size' => $row['size'],
            'quantity' => $row['quantity'],
            'price' => $row['price']
        ];
    }

    echo json_encode([
        'success' => true,
        'receipt' => [
            'id' => $receipt['id'],
            'supplier_name' => $receipt['supplier_name'],
            'employee' => $receipt['employee'],
            'receipt_date' => $receipt['receipt_date'],
            'discount_percent' => $receipt['discount_percent'],
            'total_amount' => $receipt['total_amount'],
            'notes' => $receipt['notes']
        ],
        'details' => $details
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Hành động không hợp lệ']);
}

$conn->close();
