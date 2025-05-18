<?php
require_once 'Supplier.php';
require_once '../../connect.php';

$action = $_GET['action'] ?? '';
$keyword = $_GET['keyword'] ?? '';
$product_id = $_GET['product_id'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'supplier':
        if (!$keyword) {
            echo json_encode(['success' => false, 'error' => 'Thiếu từ khóa']);
            break;
        }

        try {
            $stmt = $conn->prepare("SELECT id, name FROM suppliers WHERE 
                deleted = 0 AND 
                ( id LIKE CONCAT('%', ?, '%') OR 
                name LIKE CONCAT('%', ?, '%') OR 
                email LIKE CONCAT('%', ?, '%') OR 
                phone LIKE CONCAT('%', ?, '%') ) ");

            $stmt->bind_param("ssss", $keyword, $keyword, $keyword, $keyword);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            echo json_encode(['success' => true, 'results' => $data]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'product':
        if (!$keyword) {
            echo json_encode(['success' => false, 'error' => 'Thiếu từ khóa']);
            break;
        }

        try {
            $stmt = $conn->prepare("SELECT id, name FROM products WHERE 
                deleted = 0 AND 
                ( id LIKE CONCAT('%', ?, '%') OR 
                name LIKE CONCAT('%', ?, '%') )");

            $stmt->bind_param("ss", $keyword, $keyword);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            echo json_encode(['success' => true, 'results' => $data]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'sizes':
        if (!$product_id) {
            echo json_encode(['success' => false, 'error' => 'Thiếu product_id']);
            break;
        }

        try {
            $stmt = $conn->prepare("SELECT id, size FROM product_size WHERE product_id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            echo json_encode(['success' => true, 'results' => $data]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Hành động không hợp lệ']);
        break;
}
