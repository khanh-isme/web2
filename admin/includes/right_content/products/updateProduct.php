<?php
require_once '../../connect.php';
require_once 'Product.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['name'], $_POST['price'], $_POST['category_id'], $_POST['collection_id'], $_POST['description'], $_POST['gender'])) {
        $productObj = new Product($conn);

        // Xử lý ảnh (sẽ bổ sung và sữa chữa sau)
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $uploadDir = '../uploads/';
            $imagePath = $uploadDir . $imageName;

            if (!move_uploaded_file($imageTmpPath, $imagePath)) {
                echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
                exit;
            }
        }

        $result = $productObj->updateProduct(
            $_POST['id'],
            $_POST['name'],
            $_POST['category_id'],
            $_POST['price'],
            $_POST['description'],
            $_POST['collection_id'],
            $_POST['gender'],
            $imagePath
        );

        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
