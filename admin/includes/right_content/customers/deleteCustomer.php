<?php
// --- Tệp: includes/deleteCustomer.php (Đã cập nhật cho Soft Delete) ---

include '../../connect.php'; // Kết nối CSDL

header('Content-Type: application/json'); // Trả về JSON

$response = ['status' => 'error', 'message' => 'Invalid Request.'];

// Chỉ xử lý nếu là POST và có customer_id
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['customer_id'])) {
    $customerId = filter_var($_POST['customer_id'], FILTER_VALIDATE_INT); // Lấy và lọc ID

    if ($customerId === false || $customerId <= 0) {
        $response['message'] = 'Invalid Customer ID.';
    } else {
        // --- THAY ĐỔI CHÍNH Ở ĐÂY ---
        // Chuẩn bị câu lệnh UPDATE để đổi status thành 'deleted'
        $newStatus = 'deleted';
        $sql = "UPDATE users SET status = ? WHERE id = ?";
        // ---------------------------

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // --- THAY ĐỔI CHÍNH Ở ĐÂY ---
            $stmt->bind_param("si", $newStatus, $customerId); // s = string (cho status), i = integer (cho id)
            // ---------------------------

            if ($stmt->execute()) {
                // Kiểm tra xem có hàng nào thực sự bị cập nhật không
                if ($stmt->affected_rows > 0) {
                    $response['status'] = 'success';
                    $response['message'] = 'Customer marked as deleted successfully.'; // Thay đổi thông báo
                } else {
                    // Không tìm thấy khách hàng hoặc trạng thái đã là 'deleted'
                    $response['message'] = 'Customer not found or status already set.';
                }
            } else {
                $response['message'] = 'Error executing update statement: ' . $stmt->error;
                $response['sql_error'] = $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = 'Error preparing update statement: ' . $conn->error;
            $response['sql_error'] = $conn->error;
        }
    }
} else {
    $response['message'] = 'Invalid request method or missing customer ID.';
}

$conn->close();

// Trả về kết quả
echo json_encode($response);
exit();
