<?php
require_once '../../connect.php';

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['receipt']) || !isset($data['details'])) {
    echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ']);
    exit;
}

$supplier_id = $data['receipt']['supplier_id'] ?? null;
$discount_percent = $data['receipt']['discount_percent'] ?? 0;
$notes = $data['receipt']['notes'] ?? '';
$details = $data['details'] ?? [];

if (!$supplier_id || empty($details)) {
    echo json_encode(['success' => false, 'error' => 'Thiếu nhà cung cấp hoặc chi tiết phiếu nhập']);
    exit;
}

try {

    $conn->begin_transaction();

    $stmt = $conn->prepare("SELECT id FROM suppliers WHERE id = ?");
    $stmt->bind_param("i", $supplier_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception('Nhà cung cấp không tồn tại');
    }

    $total_amount = 0;
    $processed_details = [];
    foreach ($details as $detail) {
        $product_id = $detail['product_id'] ?? 0;
        $size = $detail['size'] ?? '';
        $quantity = $detail['quantity'] ?? 0;
        $price = $detail['price'] ?? 0;
        $sell_price = $detail['sell_price'] ?? 0;

        if ($quantity < 0 || $price < 0) {
            throw new Exception('Số lượng và giá phải lớn hơn hoặc bằng 0');
        }

        $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("Product ID $product_id không tồn tại");
        }

        $stmt = $conn->prepare("SELECT id FROM product_size WHERE product_id = ? AND size = ?");
        $stmt->bind_param("is", $product_id, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO product_size (product_id, size, stock) VALUES (?, ?, 0)");
            $stmt->bind_param("is", $product_id, $size);
            $stmt->execute();
            $product_size_id = $conn->insert_id;
        } else {
            $row = $result->fetch_assoc();
            $product_size_id = $row['id'];
        }

        $total_amount += ($quantity * $price);
        $processed_details[] = [
            'product_size_id' => $product_size_id,
            'quantity' => $quantity,
            'price' => $price,
            'sell_price' => $sell_price,
            'product_id' => $product_id
        ];
    }

    $stmt = $conn->prepare("INSERT INTO receipts (supplier_id, receipt_date, total_amount, discount_percent, notes) 
                            VALUES (?, NOW(), ?, ?, ?)");
    $stmt->bind_param("idds", $supplier_id, $total_amount, $discount_percent, $notes);
    $stmt->execute();
    $receipt_id = $conn->insert_id;

    $stmt = $conn->prepare("INSERT INTO receipt_details (receipt_id, product_size_id, quantity, price) 
                            VALUES (?, ?, ?, ?)");
    foreach ($processed_details as $detail) {
        $product_size_id = $detail['product_size_id'];
        $quantity = $detail['quantity'];
        $price = $detail['price'];
        $sell_price = $detail['sell_price'];
        $product_id = $detail['product_id'];

        $stmt->bind_param("iiid", $receipt_id, $product_size_id, $quantity, $price);
        $stmt->execute();

        $updateStmt = $conn->prepare("UPDATE product_size SET stock = stock + ? WHERE id = ?");
        $updateStmt->bind_param("ii", $quantity, $product_size_id);
        $updateStmt->execute();

        if ($sell_price > 0) {
            $updateStmt = $conn->prepare("UPDATE products SET price = ? WHERE id = ?");
            $updateStmt->bind_param("di", $sell_price, $product_id);
            $updateStmt->execute();
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'receipt_id' => $receipt_id]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
$conn->close();
