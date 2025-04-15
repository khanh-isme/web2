<?php
// Kết nối cơ sở dữ liệu
require_once 'includes/connect.php';

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thay thế phần kiểm tra đăng nhập cũ bằng phần mới
session_start();
require_once("includes/admin_check_exist.php");
require_once("includes/getPermission.php");
require_once("includes/getUser.php");

// Xử lý phản hồi Ajax nếu được gọi thông qua AJAX
if(isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    if (isset($_SESSION['user']) && checkUserExist($_SESSION['user']['username'])) 
    {
        $user = getUser($_SESSION['user']['username']);
        require_once('responseHTML.php');
        $perms = getPermissions($_SESSION['user']['username']);
        if($user['status']==='active')
        {
            $response = [
                'status' => 'success',
                'message' => '<p><i class="fa-regular fa-circle-check green icon"></i>Chào mừng trở lại, '.htmlspecialchars($user['fullname'], ENT_QUOTES, 'UTF-8').' huynh!</p>',
                'html' => responseHTML($perms,$user),
                'default' => !empty($perms)?$perms[0]:'',
            ];
        }
        else
        {
            session_destroy();
            $response = [
                'status' => 'error',
                'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Tài khoản '.htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8').' đã bị khóa!</p>',
                'html' => file_get_contents("admin_login.php"),
                'default' => '',
            ];
        }
    }
    else
    {
        session_destroy();
        $response = [
            'status' => 'error',
            'message' => '',
            'html' => file_get_contents("admin_login.php"),
            'default' => '',
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Kiểm tra người dùng đã đăng nhập chưa (cho phiên hiển thị thông thường)
if (!isset($_SESSION['user']) || !checkUserExist($_SESSION['user']['username'])) {
    // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
    header("Location: login.php");
    exit;
}

$user = getUser($_SESSION['user']['username']);
if ($user['status'] !== 'active') {
    // Nếu tài khoản bị khóa, đăng xuất và chuyển hướng
    session_destroy();
    header("Location: login.php?error=account_locked");
    exit;
}

$customer_id = $user['id']; // Giả sử id khách hàng lưu trong user

// Lấy thông tin khách hàng
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
} else {
    echo "Không tìm thấy thông tin khách hàng";
    exit;
}

// Xử lý cập nhật thông tin khách hàng
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $update_sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $name, $email, $phone, $address, $customer_id);
    
    if ($update_stmt->execute()) {
        $success_message = "Thông tin đã được cập nhật thành công!";
        
        // Cập nhật lại thông tin khách hàng sau khi cập nhật
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();
    } else {
        $error_message = "Có lỗi xảy ra: " . $conn->error;
    }
}

// Xử lý thay đổi mật khẩu
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Kiểm tra mật khẩu hiện tại
    $check_sql = "SELECT password FROM users WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $customer_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $current_pwd = $check_result->fetch_assoc()['password'];
    
    if ($current_password === $current_pwd) {
        if ($new_password === $confirm_password) {
            $pwd_sql = "UPDATE users SET password = ? WHERE id = ?";
            $pwd_stmt = $conn->prepare($pwd_sql);
            $pwd_stmt->bind_param("si", $new_password, $customer_id);
    
            if ($pwd_stmt->execute()) {
                $success_message = "Mật khẩu đã được thay đổi thành công!";
            } else {
                $error_message = "Có lỗi xảy ra khi thay đổi mật khẩu: " . $conn->error;
            }
        } else {
            $error_message = "Mật khẩu mới không khớp với xác nhận mật khẩu!";
        }
    }
     else {
        $error_message = "Mật khẩu hiện tại không chính xác!";
    }
}

//Lấy danh sách đơn hàng của khách hàng
$orders_sql = "SELECT o.order_id, o.order_date, o.total_amount, o.status 
               FROM orders o 
               WHERE o.customer_id = ? 
               ORDER BY o.order_date DESC";
$orders_stmt = $conn->prepare($orders_sql);
$orders_stmt->bind_param("i", $customer_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
$orders = [];

while ($row = $orders_result->fetch_assoc()) {
    $orders[] = $row;
}

// Xử lý hủy đơn hàng
if (isset($_POST['cancel_order']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    
    // Kiểm tra xem đơn hàng có thuộc về khách hàng này không
    $check_order_sql = "SELECT order_id FROM orders WHERE order_id = ? AND customer_id = ?";
    $check_order_stmt = $conn->prepare($check_order_sql);
    $check_order_stmt->bind_param("ii", $order_id, $customer_id);
    $check_order_stmt->execute();
    $check_order_result = $check_order_stmt->get_result();
    
    if ($check_order_result->num_rows > 0) {
        // Chỉ cho phép hủy đơn hàng nếu trạng thái đang là "pending" hoặc "processing"
        $cancel_sql = "UPDATE orders SET status = 'canceled' 
                       WHERE order_id = ? AND (status = 'pending' OR status = 'processing')";
        $cancel_stmt = $conn->prepare($cancel_sql);
        $cancel_stmt->bind_param("i", $order_id);
        
        if ($cancel_stmt->execute() && $cancel_stmt->affected_rows > 0) {
            $success_message = "Đơn hàng #" . $order_id . " đã bị hủy!";
            
            // Cập nhật lại danh sách đơn hàng
            $orders_stmt->execute();
            $orders_result = $orders_stmt->get_result();
            $orders = [];
            
            while ($row = $orders_result->fetch_assoc()) {
                $orders[] = $row;
            }
        } else {
            $error_message = "Không thể hủy đơn hàng #" . $order_id . ". Đơn hàng có thể đã được xử lý!";
        }
    } else {
        $error_message = "Đơn hàng không tồn tại hoặc không thuộc về bạn!";
    }
}

//Xem chi tiết đơn hàng
$order_details = [];
if (isset($_GET['view_order']) && is_numeric($_GET['view_order'])) {
    $view_order_id = $_GET['view_order'];
    
    // Kiểm tra xem đơn hàng có thuộc về khách hàng này không
    $check_order_sql = "SELECT order_id FROM orders WHERE order_id = ? AND customer_id = ?";
    $check_order_stmt = $conn->prepare($check_order_sql);
    $check_order_stmt->bind_param("ii", $view_order_id, $customer_id);
    $check_order_stmt->execute();
    $check_order_result = $check_order_stmt->get_result();
    
    if ($check_order_result->num_rows > 0) {
        // Lấy thông tin cơ bản của đơn hàng
        $order_sql = "SELECT * FROM orders WHERE order_id = ?";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("i", $view_order_id);
        $order_stmt->execute();
        $order_result = $order_stmt->get_result();
        $order_info = $order_result->fetch_assoc();
        
        // Lấy chi tiết đơn hàng
        $details_sql = "SELECT oi.*, p.name, p.image 
                        FROM order_detail oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?";
        $details_stmt = $conn->prepare($details_sql);
        $details_stmt->bind_param("i", $view_order_id);
        $details_stmt->execute();
        $details_result = $details_stmt->get_result();
        
        while ($detail = $details_result->fetch_assoc()) {
            $order_details[] = $detail;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #e9ecef;
    color: #333;
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

header {
    background-color: #fff;
    padding: 15px 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

header h1 {
    margin: 0;
    color: #444;
    font-size: 24px;
}

.tab-container {
    display: flex;
    margin-bottom: 20px;
}

.tab {
    padding: 12px 24px;
    cursor: pointer;
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    margin-right: 5px;
    transition: all 0.3s ease;
    border-radius: 4px 4px 0 0;
}

.tab.active {
    background-color: #fff;
    border-bottom: 1px solid #fff;
    font-weight: bold;
    color: #555;
}

.tab-content {
    display: none;
    background-color: #fff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 0 4px 4px 4px;
    margin-top: -1px;
}

.tab-content.active {
    display: block;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="password"],
input[type="tel"],
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    background-color: #fafafa;
}

button, .btn {
    padding: 10px 20px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

button:hover, .btn:hover {
    background-color: #5a6268;
}

.btn-danger {
    background-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
}

.btn-secondary {
    background-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f8f9fa;
}

tr:hover {
    background-color: #f1f1f1;
}

.order-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-processing {
    background-color: #cce5ff;
    color: #004085;
}

.status-shipped {
    background-color: #d1ecf1;
    color: #0c5460;
}

.status-delivered {
    background-color: #d4edda;
    color: #155724;
}

.status-canceled {
    background-color: #f8d7da;
    color: #721c24;
}

.order-details {
    margin-top: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 4px;
}

.order-details h3 {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}

.order-item {
    display: flex;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.order-item-image {
    width: 80px;
    height: 80px;
    margin-right: 15px;
    border: 1px solid #ddd;
    padding: 2px;
}

.order-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-item-details {
    flex-grow: 1;
}

.order-total {
    text-align: right;
    font-weight: bold;
    margin-top: 15px;
    font-size: 18px;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #555;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    .tab {
        padding: 8px 16px;
    }

    .order-item {
        flex-direction: column;
    }

    .order-item-image {
        margin-bottom: 10px;
    }
}

    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Quản lý tài khoản</h1>
        </div>
    </header>
    
    <div class="container">
        <?php if(isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['view_order']) && !empty($order_details)): ?>
            <!-- Hiển thị chi tiết đơn hàng -->
            <div class="order-details">
                <h3>Chi tiết đơn hàng #<?php echo $view_order_id; ?></h3>
                <p><strong>Ngày đặt hàng:</strong> <?php echo date('d/m/Y H:i', strtotime($order_info['order_date'])); ?></p>
                <p><strong>Trạng thái:</strong> 
                    <span class="order-status status-<?php echo strtolower($order_info['status']); ?>">
                        <?php
                            switch($order_info['status']) {
                                case 'pending':
                                    echo 'Đang chờ xử lý';
                                    break;
                                case 'processing':
                                    echo 'Đang xử lý';
                                    break;
                                case 'shipped':
                                    echo 'Đã giao cho ĐVVC';
                                    break;
                                case 'delivered':
                                    echo 'Đã giao hàng';
                                    break;
                                case 'canceled':
                                    echo 'Đã hủy';
                                    break;
                                default:
                                    echo $order_info['status'];
                            }
                        ?>
                    </span>
                </p>
                <p><strong>Địa chỉ giao hàng:</strong> <?php echo $order_info['shipping_address']; ?></p>
                
                <h4 style="margin-top: 20px;">Sản phẩm trong đơn hàng:</h4>
                <?php foreach($order_details as $item): ?>
                    <div class="order-item">
                        <div class="order-item-image">
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                        </div>
                        <div class="order-item-details">
                            <h4><?php echo $item['name']; ?></h4>
                            <p>Giá: <?php echo number_format($item['price'], 0, ',', '.'); ?>đ</p>
                            <p>Số lượng: <?php echo $item['quantity']; ?></p>
                            <p>Thành tiền: <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</p>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="order-total">
                    Tổng thanh toán: <?php echo number_format($order_info['total_amount'], 0, ',', '.'); ?>đ
                </div>
                
                <?php if($order_info['status'] == 'pending' || $order_info['status'] == 'processing'): ?>
                    <form method="post" style="margin-top: 20px;">
                        <input type="hidden" name="order_id" value="<?php echo $view_order_id; ?>">
                        <button type="submit" name="cancel_order" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                            Hủy đơn hàng
                        </button>
                    </form>
                <?php endif; ?>
                
                <a href="customer.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Quay lại trang quản lý
                </a>
            </div>
        <?php else: ?>
            <!-- Hiển thị tab menu -->
            <div class="tab-container">
                <div class="tab active" data-tab="profile">Thông tin tài khoản</div>
                <div class="tab" data-tab="password">Đổi mật khẩu</div>
                <div class="tab" data-tab="orders">Đơn hàng của tôi</div>
            </div>
            
            <!-- Tab thông tin tài khoản -->
            <div id="profile" class="tab-content active">
                <h2>Thông tin tài khoản</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="name">Họ tên:</label>
                        <input type="text" id="name" name="name" value="<?php echo $customer['name']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $customer['email']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại:</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo $customer['phone']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Địa chỉ:</label>
                        <textarea id="address" name="address" rows="3"><?php echo $customer['address']; ?></textarea>
                    </div>
                    
                    <button type="submit" name="update_profile">Cập nhật thông tin</button>
                </form>
            </div>
            
            <!-- Tab đổi mật khẩu -->
            <div id="password" class="tab-content">
                <h2>Đổi mật khẩu</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" name="change_password">Đổi mật khẩu</button>
                </form>
            </div>
            
            <!-- Tab đơn hàng của tôi -->
            <div id="orders" class="tab-content">
                <h2>Đơn hàng của tôi</h2>
                
                <?php if(count($orders) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['order_id']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                                    <td>
                                        <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                                            <?php
                                                switch($order['status']) {
                                                    case 'pending':
                                                        echo 'Đang chờ xử lý';
                                                        break;
                                                    case 'processing':
                                                        echo 'Đang xử lý';
                                                        break;
                                                    case 'shipped':
                                                        echo 'Đã giao cho ĐVVC';
                                                        break;
                                                    case 'delivered':
                                                        echo 'Đã giao hàng';
                                                        break;
                                                    case 'canceled':
                                                        echo 'Đã hủy';
                                                        break;
                                                    default:
                                                        echo $order['status'];
                                                }
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="?view_order=<?php echo $order['order_id']; ?>" class="btn btn-secondary">Xem chi tiết</a>
                                        
                                        <?php if($order['status'] == 'pending' || $order['status'] == 'processing'): ?>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                <button type="submit" name="cancel_order" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                                    Hủy đơn
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Bạn chưa có đơn hàng nào.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Xử lý tab
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to current tab and content
                    this.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Auto-hide alert messages after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            if (alerts.length > 0) {
                setTimeout(function() {
                    alerts.forEach(alert => {
                        alert.style.opacity = '0';
                        alert.style.transition = 'opacity 0.5s';
                        setTimeout(() => {
                            alert.style.display = 'none';
                        }, 500);
                    });
                }, 5000);
            }
        });
    </script>
</body>
</html>