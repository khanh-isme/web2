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

            <div class="filter-header" onclick="toggleSize()">Gender</div>
            <div class="filter-options" id="gender-options">
                <a class="filter-options-element" href="">Men</a>
                <a class="filter-options-element" href="#">Women</a>
                <a class="filter-options-element"  href="#">Unisex</a>
            </div>

            <div class="filter-header-color" onclick="toggleSize1()">Category</div>
            <div class="filter-options" id="color-options">
                <a class="filter-options-element"  href="#">Sport</a>
                <a class="filter-options-element"  href="#">Fashion</a>
            </div>

            <div class="filter-header-collection" onclick="toggleSize2()">Collection</div>
            <div class="filter-options" id="collection-options">
                <a class="filter-options-element" href="#">Winter</a>
                <a class="filter-options-element" href="#">Summer</a>
                <a class="filter-options-element" href="#">Spring</a>
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
            document.addEventListener('DOMContentLoaded', function() {
                const productsPerPage = 7;
                let currentPage = 1;

                async function fetchProducts(page) {
                    try {
                        
                        const response = await fetch(`/web2/includes/get_products.php?page=${page}&limit=${productsPerPage}`);
                        console.log('Response:', response);

                        const data = await response.json();
                        console.log('Data received:', data);

                        if (data.products) {
                            displayProducts(data.products, data.totalPages);
                        } else {
                            console.error('API error:', data.message);
                        }
                    } catch (error) {
                        console.error('Lỗi khi tải sản phẩm:', error);
                    }
                }

                function displayProducts(products, totalPages) {
                    const productContainer = document.getElementById('product-for-shop');
                    if (!productContainer) {
                        console.error('Product container not found');
                        return;
                    }
                    productContainer.innerHTML = '';

                    products.forEach(product => {
                        const productHTML = `
                        <div class="product">
                            <a href="#">
                                <img src="${product.image}" alt="${product.name}">
                                <h3>${product.name}</h3>
                                <p>${product.description}</p>
                                <p class="price">${product.price}</p>
                            </a>
                        </div>
                    `;
                        productContainer.innerHTML += productHTML;
                    });

                    updatePaginationButtons(totalPages);
                }

                function updatePaginationButtons(totalPages) {
                    document.getElementById('prevBtn').disabled = currentPage === 1;
                    document.getElementById('nextBtn').disabled = currentPage === totalPages;
                    document.getElementById('currentPage').textContent = currentPage;
                }

                window.changePage = function(step) {
                    currentPage += step;
                    fetchProducts(currentPage);
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                };

                document.querySelector('.product-list').insertAdjacentHTML('beforeend', `
                <div class="pagination">
                    <button id="prevBtn" onclick="changePage(-1)">Previous</button>
                    <span>Page <span id="currentPage"></span></span>
                    <button id="nextBtn" onclick="changePage(1)">Next</button>
                </div>
            `);

                fetchProducts(currentPage);
            });
        </script>


    </div>

</body>

</html>

<?php
include '../includes/footer.php';
?>