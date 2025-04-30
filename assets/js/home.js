







async function fetchData() {
    try {
        // Gọi API lấy danh sách sản phẩm
        const response = await fetch('/web2/includes/get_products.php');
        
        if (!response.ok) {
            throw new Error(`Lỗi HTTP: ${response.status}`);
        }

        const data = await response.json();

        if (!data.products || !Array.isArray(data.products) || data.products.length === 0) {
            throw new Error("Danh sách sản phẩm trống hoặc không hợp lệ.");
        }

        console.log("Danh sách sản phẩm:", data.products);

        // Gọi API lấy danh mục (nếu cần)
        const categoriesRes = await fetch('/web2/includes/get_categories.php');
        const categories = await categoriesRes.json();

        renderProducts(data.products, categories);
        renderProductSale(data.products);
    } catch (error) {
        console.error("Lỗi khi tải dữ liệu:", error);
    }
}

function renderProducts(products, categories) {
    const productContainer = document.getElementById("product-list");
    productContainer.innerHTML = '';

    products.slice(0, 5).forEach(product => {
        const category = categories.find(c => c.id == product.category_id);
        const categoryName = category ? category.name : "Chưa xác định";

        const productHTML = `
            <div class="product-a">
                <a href="#" class="product-link" data-product-id="${product.id}">
                    <img src="${product.image || 'default.jpg'}" alt="${product.name}">
                    <h3>${product.name}</h3>
                    <p>${categoryName}</p>
                    <p class="price">${product.price}</p>
                </a>
            </div>
        `;
        productContainer.innerHTML += productHTML;
    });

    attachProductClickEvent();
}

function attachProductClickEvent() {
    document.querySelectorAll('.product-link').forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault(); // Ngăn chặn tải lại trang

            let productId = this.dataset.productId; // Lấy ID từ data attribute

            if (!productId) {
                console.error("Lỗi: Không tìm thấy ID sản phẩm.");
                return;
            }

            // Gửi AJAX đến product.php
            fetch(`/web2/pages/product.php?id=${productId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('content').innerHTML = html;
                })
                .catch(error => console.error('Lỗi khi tải sản phẩm:', error));
        });
    });
}

function renderProductSale(products) {
    const productContainer1 = document.getElementById("product-list-1");
    productContainer1.innerHTML = '';

    products.forEach(product => {
        const productHTML = `
            <div class="product">
                <a href="#" class="product-link" data-product-id="${product.id}">
                    <img src="${product.image || 'default.jpg'}" alt="${product.name}">
                </a>
                <div class="product-text">
                    <h5> Sale</h5>
                </div>
                <div class="heart-icon">
                    <i class='bx bx-heart'></i>
                </div>
                <div class="ratting">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bxs-star-half'></i>
                </div>
                <div class="price">
                    <h4>${product.name}</h4>
                    <p>${product.price}</p>
                </div>
            </div>
        `;
        productContainer1.innerHTML += productHTML;
    });

    attachProductClickEvent(); // Thêm sự kiện click cho sản phẩm trong phần sale
}

const navLinks = document.querySelectorAll(".nav-link");

    navLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const page = this.dataset.page;

            fetch(`/web2/pages/${page}`)
                .then(response => response.text())
                .then(html => {
                    const contentDiv = document.getElementById("content");
                    if (contentDiv) {
                        contentDiv.innerHTML = html;
                        console.log(`✅ Đã cập nhật nội dung ${page}`);

                        if (page.includes("shop") && typeof initShopScript === "function") {
                            resetFilters();
                            initShopScript();
                        }
                    }
                })
                .catch(error => console.error(`❌ Lỗi khi tải ${page}:`, error));
        });
    });


    
document.addEventListener("DOMContentLoaded", fetchData());

