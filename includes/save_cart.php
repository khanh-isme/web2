<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

// Lấy dữ liệu JSON gửi từ client
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['cart']) || !is_array($data['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$cart = $data['cart'];
$user_id = $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

// Nếu cart rỗng thì xóa toàn bộ giỏ hàng của user khỏi database
if (empty($cart)) {
    $deleteStmt = $connection->prepare("DELETE FROM cart WHERE user_id = ?");
    $deleteStmt->bind_param("i", $user_id);

    if ($deleteStmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Đã xóa giỏ hàng vì trống']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xóa giỏ hàng: ' . $deleteStmt->error]);
    }
    exit;
}

// Nếu cart không rỗng, tiến hành thêm/cập nhật từng sản phẩm
foreach ($cart as $item) {
    $product_id = $item['product_id'];
    $product_size_id = $item['product_size_id'];
    $quantity = $item['quantity'];
    $added_at = $item['added_at'];

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
    $checkStmt = $connection->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ? AND product_size_id = ?");
    $checkStmt->bind_param("iii", $user_id, $product_id, $product_size_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Nếu tồn tại, cập nhật số lượng
        $existing = $result->fetch_assoc();
        $new_quantity = $quantity;

        $updateStmt = $connection->prepare("UPDATE cart SET quantity = ?, added_at = ? WHERE id = ?");
        $updateStmt->bind_param("isi", $new_quantity, $added_at, $existing['id']);

        if (!$updateStmt->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật giỏ hàng: ' . $updateStmt->error]);
            exit;
        }
    } else {
        // Nếu chưa tồn tại, thêm mới
        $insertStmt = $connection->prepare("INSERT INTO cart (user_id, product_id, product_size_id, quantity, added_at) VALUES (?, ?, ?, ?, ?)");
        $insertStmt->bind_param("iiiis", $user_id, $product_id, $product_size_id, $quantity, $added_at);

        if (!$insertStmt->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm vào giỏ hàng: ' . $insertStmt->error]);
            exit;
        }
    }
}

echo json_encode(['status' => 'success', 'message' => 'Lưu giỏ hàng thành công']);
exit;
?>
