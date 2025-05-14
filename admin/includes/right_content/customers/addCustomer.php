<?php
// --- Tệp: includes/addCustomer.php ---

// Bao gồm tệp kết nối cơ sở dữ liệu
include '../../connect.php'; // Đảm bảo đường dẫn này chính xác

// Thiết lập header để trả về JSON
header('Content-Type: application/json');

// Khởi tạo mảng phản hồi
$response = ['status' => 'error', 'message' => 'Invalid request method.'];

// Chỉ xử lý nếu phương thức là POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Lấy và làm sạch dữ liệu đầu vào ---
    // Sử dụng filter_input để an toàn hơn hoặc dùng ?? '' như trước
    $username = trim($_POST['username'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? ''); // Để chuỗi rỗng nếu không có
    $address = trim($_POST['address'] ?? ''); // Để chuỗi rỗng nếu không có

    // --- Xác thực dữ liệu phía Server ---
    $errors = [];
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    // Optional: Thêm các quy tắc xác thực khác (độ dài mật khẩu, định dạng số điện thoại,...)

    // --- Kiểm tra Email tồn tại ---
    if (empty($errors)) {
        $sql_check = "SELECT id FROM users WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);

        if ($stmt_check) {
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $errors[] = "Email address already exists.";
            }
            $stmt_check->close();
        } else {
            // Lỗi nghiêm trọng khi chuẩn bị câu lệnh kiểm tra
            $response['message'] = "Database error checking email: " . $conn->error;
            echo json_encode($response);
            $conn->close(); // Đóng kết nối
            exit(); // Dừng thực thi
        }
    }

    // --- Xử lý thêm vào CSDL nếu không có lỗi ---
    if (empty($errors)) {
        // Băm mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Chuyển chuỗi rỗng thành NULL nếu cột trong DB cho phép NULL
        $phone_to_insert = ($phone === '') ? null : $phone;
        $address_to_insert = ($address === '') ? null : $address;

        // Chuẩn bị câu lệnh INSERT
        $sql = "INSERT INTO users (username, name, email, password, phone, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Gán tham số (s = string)
            $stmt->bind_param("ssssss", $username, $name, $email, $hashed_password, $phone_to_insert, $address_to_insert);

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                // Thành công
                $response['status'] = 'success';
                $response['message'] = 'Customer added successfully!';
                // Optional: trả về ID khách hàng mới nếu cần
                // $response['new_customer_id'] = $conn->insert_id;
            } else {
                // Lỗi khi thực thi
                $response['message'] = "Error adding customer: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Lỗi khi chuẩn bị câu lệnh INSERT
            $response['message'] = "Error preparing insert statement: " . $conn->error;
        }
    } else {
        // Có lỗi xác thực
        $response['status'] = 'validation_error';
        $response['message'] = 'Please correct the errors below.';
        $response['errors'] = $errors; // Gửi danh sách lỗi về client
    }
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();

// Trả về phản hồi dưới dạng JSON
echo json_encode($response);
exit(); // Dừng script

?>