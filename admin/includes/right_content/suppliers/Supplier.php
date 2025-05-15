<?php
require_once '../../connect.php';

class Supplier
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllSuppliers()
    {
        $query = "SELECT id, name, address, email, phone, contact, created_at FROM suppliers WHERE deleted = 0";
        $result = $this->db->query($query);
        if (!$result) {
            throw new Exception("Lỗi truy vấn SQL: " . $this->db->error);
        }
        $suppliers = [];
        while ($row = $result->fetch_assoc()) {
            $suppliers[] = $row;
        }
        return $suppliers;
    }

    public function getSupplierById($id)
    {
        $query = "SELECT id, name, address, email, phone, contact, created_at FROM suppliers WHERE id = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new Exception("Lỗi chuẩn bị truy vấn: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi thực thi truy vấn: " . $stmt->error);
        }
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addSupplier($name, $address, $email, $phone, $contact)
    {
        $query = "INSERT INTO suppliers (name, address, email, phone, contact) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new Exception("Lỗi chuẩn bị truy vấn: " . $this->db->error);
        }
        $stmt->bind_param("sssss", $name, $address, $email, $phone, $contact);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi thực thi truy vấn: " . $stmt->error);
        }
        return true;
    }

    public function updateSupplier($id, $name, $address, $email, $phone, $contact)
    {
        $query = "UPDATE suppliers SET name = ?, address = ?, email = ?, phone = ?, contact = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new Exception("Lỗi chuẩn bị truy vấn: " . $this->db->error);
        }
        $stmt->bind_param("sssssi", $name, $address, $email, $phone, $contact, $id);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi thực thi truy vấn: " . $stmt->error);
        }
        return true;
    }

    public function deleteSupplier($id)
    {
        $query = "UPDATE suppliers SET deleted = 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new Exception("Lỗi chuẩn bị truy vấn: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi thực thi truy vấn: " . $stmt->error);
        }
        return true;
    }
}
