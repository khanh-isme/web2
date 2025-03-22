<!-- dùng để quản lý data cho toàn bộ trang web -->

<?php

class Products {
    private $id;
    private $name;
    private $category_id;
    private $price;
    private $description;
    private $image;
    private $created_at;
    private $category;
    private $collection_id;
    private $gender;

    public function __construct($id, $name, $category_id, $price, $description, $image, $created_at, $category, $collection_id, $gender ) {
        $this->id = $id;
        $this->name = $name;
        $this->category_id = $category_id;
        $this->price = $price;
        $this->description = $description;
        $this->image = $image;
        $this->created_at = $created_at;
        $this->category = $category;
        $this->collection_id = $collection_id;
        $this->gender = $gender;

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
    $database = "shoe";

    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
        die("Kết nối thất bại: " . $connection->connect_error);
    } else {
        echo "Kết nối thành công!<br>";
    }

    function getProductList($connection) {
        $strSQL = "SELECT * FROM products";
        $result = $connection->query($strSQL);
    
        $productList = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productList[] = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'category_id' => $row['category_id'],
                    'price' => number_format($row['price'], 0, ',', '.') . 'đ', 
                    'description' => $row['description'],
                    'image' => $row['image'],
                    'created_at' => $row['created_at'],
                    'category' => $row['category_id'], 
                    'collection_id' => $row['collection_id'],
                    'gender' => $row['gender']
                );
            }
        }
        return $productList; // Trả về một mảng thay vì danh sách đối tượng Products
    }


    
    
    function getCategoryList($connection){
        $strSQL = "SELECT * FROM categories";
        $result = $connection->query($strSQL);
    
        $categoryList = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categoryList[] = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'description' => $row['description']       
                );
            }
        }
        return $categoryList; // Trả về một mảng thay vì danh sách đối tượng Products
    }


?>