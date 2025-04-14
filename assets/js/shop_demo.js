(function () {
    function initShopScript() {
        console.log("✅ Shop script initialized");

         // Kiểm tra xem script đã chạy chưa
    if (window.shopScriptInitialized) {
        console.log("🚨 Script đã chạy trước đó, bỏ qua init.");
        return;
    }
    window.shopScriptInitialized = true; // Đánh dấu script đã chạy

    resetFilters();
    fetchProducts(1);
        

        // Reset bộ lọc về mặc định
        function resetFilters() {
            window.selectedGender = '';
            window.selectedCategory = '';
            window.selectedCollection = '';


        }

        // Các toggle bộ lọc
        function toggleFilter(headerClass, optionsId) {
            const header = document.querySelector(headerClass);
            const options = document.getElementById(optionsId);
            if (header && options) {
                options.style.display = options.style.display === 'block' ? 'none' : 'block';
                header.classList.toggle('collapsed');
            }
        }

        window.toggleSize = () => toggleFilter('.filter-header', 'gender-options');
        window.toggleSize1 = () => toggleFilter('.filter-header-color', 'color-options');
        window.toggleSize2 = () => toggleFilter('.filter-header-collection', 'collection-options');

        const productsPerPage = 7;
        let currentPage = 1;
        let totalPages = 1;
        let isFetching = false;

        async function fetchProducts(page) {
            if (isFetching || page < 1 || page > totalPages) return;
            isFetching = true;

            try {
                let url = `/web2/includes/get_products.php?page=${page}&limit=${productsPerPage}`;
                if (window.selectedGender) url += `&gender=${window.selectedGender}`;
                if (window.selectedCategory) url += `&category=${encodeURIComponent(window.selectedCategory)}`;
                if (window.selectedCollection) url += `&collection=${encodeURIComponent(window.selectedCollection)}`;

                console.log("📡 Fetching:", url);

                const response = await fetch(url);
                const data = await response.json();
                
                if (data.products) {
                    displayProducts(data.products);
                    totalPages = data.totalPages;
                    updatePaginationButtons();
                } else {
                    console.error('❌ API error:', data.message);
                }
            } catch (error) {
                console.error('❌ Lỗi khi tải sản phẩm:', error);
            } finally {
                isFetching = false;
            }
        }

        function displayProducts(products) {
            const productContainer = document.getElementById('product-for-shop');
            if (!productContainer) {
                console.error("❌ Không tìm thấy phần tử #product-for-shop.");
                return;
            }

            productContainer.innerHTML = products.length === 0
                ? '<div class="product">Không có sản phẩm nào.</div>'
                : products.map(product => `
                    <div class="product" data-product-id="${product.id}">
                        <a href="#" class="product-link">
                            <img src="${product.image}" alt="${product.name}">
                            <h3>${product.name}</h3>
                            <p>${product.description}</p>
                            <p class="price">${product.price}</p>
                        </a>
                    </div>`).join('');

            console.log(`✅ Hiển thị ${products.length} sản phẩm`);
        }

        function updatePaginationButtons() {
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === totalPages;
            document.getElementById('currentPage').textContent = currentPage;
        }

        window.changePage = function (step) {
            if (!isFetching && currentPage + step >= 1 && currentPage + step <= totalPages) {
                currentPage += step;
                fetchProducts(currentPage);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        };

        function applyFilter() {
            currentPage = 1;
            fetchProducts(currentPage);
        }

        window.filterMen = () => { window.selectedGender = 'nam'; applyFilter(); };
        window.filterWomen = () => { window.selectedGender = 'nu'; applyFilter(); };
        window.filterUnisex = () => { window.selectedGender = 'unisex'; applyFilter(); };
        window.filterCategory = (category) => { window.selectedCategory = category; applyFilter(); };
        window.filterCollection = (collection) => { window.selectedCollection = collection; applyFilter(); };

        document.addEventListener('click', function (event) {
            if (event.target.closest('#color-options a')) {
                filterCategory(event.target.textContent.trim());
            }
            if (event.target.closest('#collection-options a')) {
                filterCollection(event.target.textContent);
            }
        });


        $(document).off("click", ".product a");// Xóa sự kiện click cũ để tránh xung đột
        // Xử lý sự kiện click vào sản phẩm
        $(document).on("click", ".product a", function (event) {
            event.preventDefault();
        
            let productId = $(this).closest(".product").data("product-id");
            if (!productId) {
                console.error("❌ Không tìm thấy ID sản phẩm.");
                return;
            }
        
            console.log("🛍 Sản phẩm ID:", productId);
        
            // Chỉ tải nội dung sản phẩm vào #content, KHÔNG thay đổi URL
            $("#content").load(`/web2/pages/product.php?id=${productId}`, function () {
                console.log("✅ Đã tải nội dung sản phẩm thành công!");
            });
        });
        

        // Xử lý khi người dùng bấm "Quay lại" hoặc "Tiến tới"
        
        

        // Kiểm tra nếu `shop.php` được tải qua AJAX
        $(document).ajaxComplete(function (event, xhr, settings) {
            if (settings.data && settings.data.includes("shop.php")) {
                console.log("🔄 Shop page loaded - Reset filters.");
                resetFilters();
                fetchProducts(1);
            }
        });
        

        // Gọi fetchProducts khi trang được load
        fetchProducts(currentPage);
    }

    // Khởi chạy script ban đầu
    $(document).ready(initShopScript);

})();
