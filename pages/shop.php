<?php
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <script src="../assets/js/shop.js" defer></script>
    <link rel="stylesheet" href="../assets/css/shop.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Filters</h2>
            <div class="filter-group">
                <label><input type="checkbox"> Boys</label><br>
                <label><input type="checkbox"> Girls</label>
            </div>
            
            <div class="filter-header" onclick="toggleSize()">Gender</div>
            <div class="filter-options" id="gender-options">
                <a href="shopF.html">Men</a>
                <a href="shopF.html">Women</a>
                <a href="shopF.html">Unisex</a>
            </div>

            <div class="filter-header-color" onclick="toggleSize1()">Category</div>
            <div class="filter-options" id="color-options">
                <a href="shopF.html">Sport</a>
                <a href="shopF.html">Fashion</a>
            </div>

            <div class="filter-header-collection" onclick="toggleSize2()">Collection</div>
            <div class="filter-options" id="collection-options">
                <a href="shopF.html">Winter</a>
                <a href="shopF.html">Summer</a>
                <a href="shopF.html">Spring</a>
            </div>
        </aside>

        <!-- Danh sách sản phẩm -->
        <main class="product-list">
            <div class="header">
                <h1>Shoes Vipro</h1>
            </div>

            <div class="products" id="product-for-shop"></div>
        </main>

        <script>
            const products = [
                {
                    name: "Nike Pegasus Trail 5T-Shirt 14A",
                    tag: "Hàng mới",
                    image: "/web2/assets/images/14.png",
                    price: 1000,
                    title: "Giày cho mấy nhóc già đầu đi",
                    color: "1 Mẫu màu"    
                },
                {
                    name: "Nike Pegasus Trail 5",
                    tag: "Hàng mới",
                    image: "/web2/assets/images/14.png",
                    price: 1000,
                    title: "Giày cho mấy nhóc già đầu đi",
                    color: "1 Mẫu màu"    
                },
                {
                    name: "Nike Pegasus Trail 5",
                    tag: "Hàng mới",
                    image: "/web2/assets/images/14.png",
                    price: 1000,
                    title: "Giày cho mấy nhóc già đầu đi",
                    color: "1 Mẫu màu"    
                },
                {
                    name: "Nike Pegasus Trail 5",
                    tag: "Hàng mới",
                    image: "/web2/assets/images/14.png",
                    price: 1000,
                    title: "Giày cho mấy nhóc già đầu đi",
                    color: "1 Mẫu màu"    
                },
                {
                    name: "Nike Pegasus Trail 5",
                    tag: "Hàng mới",
                    image: "/web2/assets/images/14.png",
                    price: 1000,
                    title: "Giày cho mấy nhóc già đầu đi",
                    color: "1 Mẫu màu"    
                },
                {
                    name: "Nike Pegasus Trail 5",
                    tag: "Hàng mới",
                    image: "/web2/assets/images/14.png",
                    price: 1000,
                    title: "Giày cho mấy nhóc già đầu đi",
                    color: "1 Mẫu màu"    
                }
            ];

            const productsPerPage = 3; // Số sản phẩm trên mỗi trang
            let currentPage = 1;

            function displayProducts(page) {
                const productContainer = document.getElementById('product-for-shop');
                productContainer.innerHTML = ""; // Xóa nội dung cũ

                const startIndex = (page - 1) * productsPerPage;
                const endIndex = startIndex + productsPerPage;
                const paginatedProducts = products.slice(startIndex, endIndex);

                paginatedProducts.forEach(product => {
                    const productHTML = `
                        <div class="product">
                            <a href="product.html">
                                <img src="${product.image}" alt="Shoe">
                                <p class="tag">${product.tag}</p>
                                <h3>${product.name}</h3>
                                <p>${product.title}</p>
                                <p>${product.color}</p>
                                <p class="price">${product.price}$</p>
                            </a>
                        </div>
                    `;
                    productContainer.innerHTML += productHTML;
                });

                updatePaginationButtons();
            }

            function updatePaginationButtons() {
                document.getElementById('prevBtn').disabled = currentPage === 1;
                document.getElementById('nextBtn').disabled = currentPage === Math.ceil(products.length / productsPerPage);
            }

            function changePage(step) {
                currentPage += step;
                displayProducts(currentPage);
            }

            // Thêm nút phân trang vào HTML
            document.addEventListener("DOMContentLoaded", function () {
                const paginationControls = `
                    <div class="pagination">
                        <button id="prevBtn" onclick="changePage(-1)">Previous</button>
                        <span> Page <span id="currentPage">1</span> </span>
                        <button id="nextBtn" onclick="changePage(1)">Next</button>
                    </div>
                `;
                document.querySelector('.product-list').insertAdjacentHTML('beforeend', paginationControls);
                displayProducts(currentPage);
            });

        </script>
    </div>


    

</body>
</html>

<?php
include '../includes/footer.php';       
?>