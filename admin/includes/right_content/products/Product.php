<?php
require_once '../../connect.php';
class Product
{
    private $conn;

    // Constructor để khởi tạo kết nối database
    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    // Lấy danh sách sản phẩm với thông tin liên kết
    public function getAllProducts()
    {
        $sql = "
            SELECT 
                p.id, 
                p.name, 
                p.price, 
                p.image, 
                p.gender, 
                p.description, 
                p.category_id, 
                c.name AS category_name, 
                GROUP_CONCAT(CONCAT(ps.size, ':', ps.stock) SEPARATOR ', ') AS sizes,
                SUM(ps.stock) AS total_stock
            FROM 
                products p
            LEFT JOIN 
                categories c ON p.category_id = c.id
            LEFT JOIN 
                product_size ps ON p.id = ps.product_id
            WHERE 
                p.deleted = 0
            GROUP BY 
                p.id
        ";
        $result = $this->conn->query($sql);

        $products = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        return $products;
    }

    // Lấy thông tin chi tiết sản phẩm theo ID
    public function getProductById($id)
    {
        $sql = "
            SELECT 
                p.id, 
                p.name, 
                p.price, 
                p.image, 
                p.gender, 
                p.description, 
                c.name AS category_name, 
                c.id AS category_id,
                GROUP_CONCAT(CONCAT('size ', ps.size, ' : ', ps.stock) SEPARATOR ', ') AS sizes,
                SUM(ps.stock) AS total_stock
            FROM 
                products p
            LEFT JOIN 
                categories c ON p.category_id = c.id
            LEFT JOIN 
                product_size ps ON p.id = ps.product_id
            WHERE 
                p.id = ?
            GROUP BY 
                p.id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Thêm sản phẩm mới
    public function addProduct($name, $category_id, $price, $description, $image, $gender)
    {
        $sql = "
            INSERT INTO products (name, category_id, price, description, image, gender, deleted) 
            VALUES (?, ?, ?, ?, ?, ?, 0)
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sidsss", $name, $category_id, $price, $description, $image, $gender);
        return $stmt->execute();
    }

    // chua xử lý cập nhật img
    public function updateProduct($id, $name, $category_id, $price, $description, $gender, $image = null)
    {
        $sql = "UPDATE products 
                SET name = ?, category_id = ?, price = ?, description = ?, gender = ?";

        // Nếu có ảnh mới, thêm vào câu lệnh SQL
        if ($image) {
            $sql .= ", image = ?";
        }

        $sql .= " WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        // Nếu có ảnh mới, bind thêm tham số ảnh
        if ($image) {
            $stmt->bind_param("sidsssi", $name, $category_id, $price, $description, $gender, $image, $id);
        } else {
            $stmt->bind_param("sidssi", $name, $category_id, $price, $description, $gender, $id);
        }

        $success = $stmt->execute();

        if ($success) {
            return ['success' => true, 'message' => "Product updated successfully."];
        } else {
            return ['success' => false, 'message' => "Failed to update product!"];
        }
    }

    public function deleteProduct($id)
    {
        $sql = "UPDATE products SET deleted = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
