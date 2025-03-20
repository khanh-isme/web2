
<?php
include '../includes/header.php';
?>

<html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/shop.css">
    <script src="../assets/js/header.js"></script>
</head>
</head>
<body>
    
    <div class="container">
        <aside class="sidebar">
            <h2>Filters</h2>
            <div class="filter-group">
            </div>
            <div class="filter-group">
                <label><input type="checkbox"> Boys</label><br>
                <label><input type="checkbox"> Girls</label>
            </div>
            
            <div class="filter-header"  onclick="toggleSize()">Gender </div>
            <div class="filter-options" id="gender-options">
                <a href="shopF.html"> Men </a>

                <a href="shopF.html"> Women </a>
                
                <a href="shopF.html">Unisex</a>
            </div>

            <div class="filter-header-color"  onclick="toggleSize1()">Category </div>
            <div class="filter-options" id="color-options">
                <a href="shopF.html"> Sport </a>

                <a href="shopF.html"> Fastion </a>
                
            
            </div>

            <div class="filter-header-collection"  onclick="toggleSize2()">Collection </div>
            <div class="filter-options" id="collection-options">
                <a href="shopF.html"> winter </a>

                <a href="shopF.html"> summer </a>
                
                <a href="shopF.html"> spring</a>
            </div>
            
        </aside>

        <main class="product-list">
            <div class="header">
                <h1> Shoes vipro </h1>
            </div>

            <div class="products">
                <div class="product">
                    <a href="product.html">

                        <img src="../images/11.png" alt="Shoe 1">
                        <p class="tag">Hàng mới</p>
                        <h3>Nike Pegasus Trail 5</h3>
                        <p>Giày cho mấy nhóc già đầu đi</p>
                        <p>1 Mẫu màu</p>
                        <p class="price">1000$</p>
                    </a>
                </div>

                <!-- Repeat product divs for other products -->
                <div class="product">
                    <a href="product.html">

                        <img src="../images/12.png" alt="Shoe 2">
                        <p class="tag">Bán chạy</p>
                        <h3>For My Pride 41</h3>
                        <p>Giày giành cho MCK</p>
                        <p>1 Mẫu màu</p>
                        <p class="price">2000$</p>
                    </a>
                </div>
                <div class="product">
                    <a href="product.html">

                        <img src="../images/7.png" alt="Shoe 2">
                        <p class="tag">Bán chạy</p>
                        <h3>For My Pride 41</h3>
                        <p>Giày giành cho MCK</p>
                        <p>1 Mẫu màu</p>
                        <p class="price">2,679$</p>
                    </a>
                </div>
                <div class="product">
                    <a href="product.html">

                    
                        <img src="../images/10.png" alt="Shoe 4">
                        <p class="tag">Bán chạy</p>
                        <h3>Nike air mag hàng faki</h3>
                        <p>Mang vào auto đẹp gái</p>
                        <p>2 Mẫu màu</p>
                        <p class="price">4,000,000$</p>
                    </a>   
                </div>
                <div class="product">
                    <a href="product.html">

                        <img src="../images/14.png" alt="Shoe 5">
                        <p class="tag">Cháy hàng</p>
                        <h3>Giày 30/4/1975</h3>
                        <p>Mang vào dinh độc lập sẽ được tặng huy chương</p>
                        <p>0 Mẫu màu</p>
                        <p class="price">7,00$</p>
                    </a>
                </div>
                <div class="product">
                    <a href="product.html">

                        <img src="../images/13.png" alt="Shoe 6">
                        <p class="tag">Bán chạy</p>
                        <h3>Nigeria</h3>
                        <p>Mang hiệu ứng quyền công dân</p>
                        <p>1 Mẫu màu</p>
                        <p class="price">999$</p>
                    </a>
                </div>
                <div class="product">
                    <a href="product.html">

                        <img src="../images/8.jpg" alt="Shoe 7">
                        <p class="tag">Ế hàng</p>
                        <h3>Giày Gengu</h3>
                        <p>Mang vào thì thua T1</p>
                        <p>1 Mẫu màu</p>
                        <p class="price">1$</p>
                    </a>
                </div>
                <div class="product">
                    <a href="product.html">

                        <img src="../images/11.png" alt="Shoe 8">
                        <p class="tag">Bán đắt</p>
                        <h3>Giày SGP oai phong</h3>
                        <p>Mang vào sẽ có 9 lần vô địch quốc nội</p>
                        <p>2 Mẫu màu</p>
                        <p class="price">999$</p>
                    </a>
                </div>
                <div class="product">
                    <a href="product.html">

                        <img src="../images/222.webp" alt="Shoe 8">
                        <p class="tag">Bán đắt</p>
                        <h3>Giày SGP oai phong</h3>
                        <p>Mang vào sẽ có 9 lần vô địch quốc nội</p>
                        <p>2 Mẫu màu</p>
                        <p class="price">999$</p>
                    </a>
                </div>

            </div>
        </main>
    </div>


</body>
</html>
</html>
