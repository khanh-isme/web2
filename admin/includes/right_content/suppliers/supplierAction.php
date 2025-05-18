<?php

require_once '../../connect.php';
require_once 'Supplier.php';

$supplier = new Supplier($conn);

$action = isset($_POST['action']) ? $_POST['action'] : '';

try {
    switch ($action) {
        case 'get_all':
            echo json_encode($supplier->getAllSuppliers());
            break;

        case 'add':
            if (empty($_POST['ten'])) {
                echo json_encode(['success' => false, 'error' => 'Tên là bắt buộc']);
                break;
            }

            $name = htmlspecialchars(strip_tags($_POST['ten']));
            $address = htmlspecialchars(strip_tags($_POST['address'] ?? ''));
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $phone = htmlspecialchars(strip_tags($_POST['phone'] ?? ''));
            $contact = htmlspecialchars(strip_tags($_POST['contact'] ?? ''));

            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'error' => 'Email không hợp lệ']);
                break;
            }

            if (strlen($contact) > 100) {
                echo json_encode(['success' => false, 'error' => 'Người liên hệ không được vượt quá 100 ký tự']);
                break;
            }

            $result = $supplier->addSupplier($name, $address, $email, $phone, $contact);
            echo json_encode(['success' => $result]);
            break;

        case 'update':
            if (empty($_POST['id']) || empty($_POST['ten'])) {
                echo json_encode(['success' => false, 'error' => 'ID và tên là bắt buộc']);
                break;
            }

            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $name = htmlspecialchars(strip_tags($_POST['ten']));
            $address = htmlspecialchars(strip_tags($_POST['address'] ?? ''));
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $phone = htmlspecialchars(strip_tags($_POST['phone'] ?? ''));
            $contact = htmlspecialchars(strip_tags($_POST['contact'] ?? ''));

            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'error' => 'Email không hợp lệ']);
                break;
            }

            if (strlen($contact) > 100) {
                echo json_encode(['success' => false, 'error' => 'Người liên hệ không được vượt quá 100 ký tự']);
                break;
            }

            $result = $supplier->updateSupplier($id, $name, $address, $email, $phone, $contact);
            echo json_encode(['success' => $result]);
            break;

        case 'delete':
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            if ($id === false) {
                echo json_encode(['success' => false, 'error' => 'ID không hợp lệ']);
                break;
            }

            $result = $supplier->deleteSupplier($id);
            echo json_encode(['success' => $result]);
            break;

        case 'get_supplier':
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            if ($id === false) {
                echo json_encode(['error' => 'ID không hợp lệ']);
                break;
            }

            $supplierData = $supplier->getSupplierById($id);
            if (!$supplierData) {
                echo json_encode(['error' => 'Nhà cung cấp không tồn tại']);
                break;
            }

            echo json_encode($supplierData);
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    error_log("Error in supplierAction.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
