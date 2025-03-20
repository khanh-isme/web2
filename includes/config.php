<!-- dùng để quản lý data cho toàn bộ trang web -->

<?php

class Products {
    private $id;
    private $name;
    private $category_id;
    private $price;
    private $stock;
    private $description;
    private $image;
    private $created_at;

    public function __construct($id, $name, $category_id, $price, $stock, $description, $image, $created_at) {
        $this->id = $id;
        $this->name = $name;
        $this->category_id = $category_id;
        $this->price = $price;
        $this->stock = $stock;
        $this->description = $description;
        $this->image = $image;
        $this->created_at = $created_at;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImage() {
        return $this->image;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getFormattedPrice() {
        return number_format($this->price, 0, ',', '.') . 'đ';
    }
}

?>

<?php
$servername = "127.0.0.1:3307"; 
$username = "root";
$password = "";
$database = "testbaitap";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Kết nối thất bại: " . $connection->connect_error);
} else {
    echo "Kết nối thành công!<br>";
}

$strSQL = "SELECT * FROM products";
$result = $connection->query($strSQL);

$productList = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product = new Products(
            $row['id'], 
            $row['name'], 
            $row['category_id'], 
            $row['price'], 
            $row['stock'], 
            $row['description'], 
            $row['image'], 
            $row['created_at']
        );
        $productList[] = $product;
    }
}

foreach ($productList as $product) {
    echo "ID: " . $product->getId() . " - Name: " . $product->getName() . "<br>";
}

// Đóng kết nối
$connection->close();
?>
