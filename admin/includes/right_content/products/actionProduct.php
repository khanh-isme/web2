<?php
require_once '../../connect.php';
require_once 'Product.php';

header('Content-Type: application/json');

// Kiểm tra phương thức yêu cầu
$method = $_SERVER['REQUEST_METHOD'];
$product = new Product($conn);

if ($method === 'POST') {
    // Đọc action từ body (JSON) hoặc form
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Nếu không phải JSON (trường hợp gửi FormData), lấy từ $_POST
    if (!$data) {
        $data = $_POST;
    }

    $action = $data['action'] ?? '';

    switch ($action) {
        // Thêm sản phẩm
        case 'add':
            // Lấy dữ liệu từ form
            $product_name = $data['product_name'] ?? '';
            $product_category = $data['product_category'] ?? '';
            $product_gender = $data['gender'] ?? '';
            $product_description = $data['product_description'] ?? '';
            $product_price = $data['product_price'] ?? 0;
            $image_src = $data['image_src'] ?? ''; // Lấy đường dẫn gốc từ client

            // Kiểm tra dữ liệu bắt buộc
            if (empty($product_name) || empty($product_category)) {
                echo json_encode(['success' => false, 'message' => 'Thiếu hoặc không hợp lệ các trường bắt buộc']);
                exit;
            }

            $image_db_path = ''; // Khởi tạo biến

            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../../../../assets/images/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $original_name = pathinfo($_FILES['product_image']['name'], PATHINFO_FILENAME);
                $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $image_name = preg_replace('/[^a-zA-Z0-9._-]/', '', $original_name) . '.' . $extension;
                $image_path = $upload_dir . $image_name;
                $image_db_path = '../assets/images/' . $image_name;

                // if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $image_path)) {
                //     echo json_encode(['success' => false, 'message' => 'Không thể upload ảnh']);
                //     exit;
                // }
            } elseif (!empty($image_src)) {
                $image_db_path = $image_src;
            } else {
                $image_db_path = '../assets/images/logo.png';
            }

            try {
                // Thêm sản phẩm bằng class Product
                $added = $product->addProduct(
                    $product_name,
                    $product_category,
                    $product_price,
                    $product_description,
                    $image_db_path,
                    $product_gender
                );

                if (!$added) {
                    throw new Exception('Không thể thêm sản phẩm mới');
                }

                echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
            }
            break;

        // Xóa sản phẩm
        case 'delete':
            $id = $data['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Thiếu ID sản phẩm']);
                exit;
            }

            try {
                // Kiểm tra sản phẩm có tồn tại không
                $stmt = $conn->prepare("SELECT id FROM products WHERE id = ? AND deleted = 0");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows === 0) {
                    throw new Exception('Sản phẩm không tồn tại hoặc đã bị xóa');
                }

                // Xóa sản phẩm bằng class Product
                $deleted = $product->deleteProduct($id);
                if (!$deleted) {
                    throw new Exception('Không thể xóa sản phẩm');
                }

                echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
            }
            break;

        // Cập nhật sản phẩm
        case 'update':
            $id = $data['id'] ?? '';
            $name = $data['name'] ?? '';
            $category_id = $data['category_id'] ?? '';
            $gender = $data['gender'] ?? '';
            $price = $data['price'] ?? 0;
            $description = $data['description'] ?? '';
            $image_src = $data['image_src'] ?? ''; // Lấy đường dẫn gốc từ client

            // Kiểm tra dữ liệu bắt buộc
            if (empty($id) || empty($name) || empty($category_id)) {
                echo json_encode(['success' => false, 'message' => 'Thiếu hoặc không hợp lệ các trường bắt buộc']);
                exit;
            }

            // Xử lý upload ảnh nếu có
            $image_name = $image_src; // Giữ nguyên ảnh cũ nếu không upload ảnh mới
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../../../../assets/images/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $original_name = pathinfo($_FILES['product_image']['name'], PATHINFO_FILENAME);
                $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $image_name = preg_replace('/[^a-zA-Z0-9._-]/', '', $original_name) . '.' . $extension;
                $image_path = $upload_dir . $image_name;
                $image_db_path = '../assets/images/' . $image_name;

                // if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $image_path)) {
                //     echo json_encode(['success' => false, 'message' => 'Không thể upload ảnh']);
                //     exit;
                // }
            } elseif (!empty($image_src)) {
                $image_db_path = $image_src;
            } else {
                $image_db_path = '../assets/images/default.png';
            }
            try {
                // Cập nhật sản phẩm bằng class Product
                $result = $product->updateProduct(
                    $id,
                    $name,
                    $category_id,
                    $price,
                    $description,
                    $gender,
                    $image_db_path // Lưu đường dẫn ảnh nếu có
                );

                if (!$result['success']) {
                    throw new Exception($result['message']);
                }

                echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm thành công']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}

$conn->close();
