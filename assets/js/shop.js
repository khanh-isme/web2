

(function () {
    window.initShopScript = function() {

        const productsPerPage = 7;
        let currentPage = 1;
        let totalPages = 1;
        let isFetching = false;
        let selectedGenderRadio = null;
        let selectedCategoryRadio = null;
        
        window.productName = window.productName || '';
        window.selectedGender = window.selectedGender || '';
        window.selectedCategory = window.selectedCategory || '';
        window.size = window.size || '';
        window.minPrice = window.minPrice || '';
        window.maxPrice = window.maxPrice || '';

        console.log(window.selectButtonFlag); 
       

        function getFilterValues() {
            window.productName = document.getElementById("productName")?.value.trim() || window.productName;

            // Lấy giá trị gender từ một input ẩn hoặc nơi nào đó trong DOM
            if(window.selectButtonFlag){
                window.selectedGender =document.getElementById("gender").value;
                window.selectedCategory= document.getElementById("category").value;
            }

            if (window.selectedGender) {
                const genderRadio = document.querySelector(`input[name="gender"][value="${window.selectedGender}"]`);//genderRadio là 1 DOM element
                if (genderRadio) {
                    genderRadio.checked = true;
                    selectedGenderRadio = genderRadio;
                    console.log("đã chọn radio ", genderRadio.value) 
                }
            }

            console.log("DEBUG selectedCategory:", window.selectedCategory);


            if (window.selectedCategory) {
                const categoryRadio = document.querySelector(`input[name="category"][value="${window.selectedCategory}"]`);
                if (categoryRadio){
                    categoryRadio.checked = true;
                    selectedCategoryRadio = categoryRadio;
                    console.log("đã chọn radio ", categoryRadio.value) 
                } 
            }

            window.size = document.getElementById("size")?.value || window.size ;
            window.minPrice = document.getElementById("minPrice")?.value ||  window.minPrice;
            window.maxPrice = document.getElementById("maxPrice")?.value ||  window.maxPrice;
            console.log("🔍 Đã cập nhật filter mới");
        }
        
        
    

        async function fetchProducts(page) {
            if (isFetching ) return;  //|| page < 1 || page > totalPages
            isFetching = true;
            getFilterValues();

            try {
                let url = `/web2/includes/get_products.php?page=${page}&limit=${productsPerPage}`;
                if(productName != ''){
                   console.log(productName); 
                   url +=`&name=${encodeURIComponent(productName)}`;
                }
                if (size) url += `&size=${encodeURIComponent(size)}`;
                if (minPrice) url += `&minPrice=${encodeURIComponent(minPrice)}`; 
                if (maxPrice) url += `&maxPrice=${encodeURIComponent(maxPrice)}`;
                if (window.selectedGender) url += `&gender=${window.selectedGender}`;
                if (window.selectedCategory) url += `&category=${encodeURIComponent(window.selectedCategory)}`;
                

                console.log("📡 Fetching:", url);

                const response = await fetch(url);
                const data = await response.json();
                console.log(data);
                
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
                console.log("isFetching đã được reset")
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

            initProductEvents() 
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

      

        // Lấy danh sách filter từ các input đang được chọn
        function getSelectedFilters() {
            const filters = {
                gender: '',
                category: ''
            };

            // Lấy giá trị radio gender được chọn
            const selectedGenderDemo = document.querySelector('input[name="gender"]:checked');
            if (selectedGenderDemo) {
                document.getElementById("gender").value = '';
                filters.gender = selectedGenderDemo.value;
                window.selectedGender = selectedGenderDemo.value;
            } else {
                filters.gender = '';
                window.selectedGender = '';
            }
            
            
            const selectedCategoryDemo =document.querySelector('input[name="category"]:checked');
            if (selectedCategoryDemo) {
                document.getElementById("category").value = '';
                filters.category = selectedCategoryDemo.value;
                window.selectedCategory = selectedCategoryDemo.value;
            } else {
                filters.category = '';
                window.selectedCategory = '';
            }
            
            return filters;
        }

        // Gọi filter 
        function applyFilter() {
            const filters = getSelectedFilters();
            console.log("Đang lọc bằng filter:", filters);
            currentPage = 1;
            fetchProducts(currentPage);
        }

        // Lắng nghe sự kiện thay đổi (checkbox hoặc radio) -> gọi applyFilter
        document.querySelectorAll('input[type="radio"]').forEach(input => {
            input.addEventListener('change', applyFilter);
        });


        

        document.querySelectorAll('input[name="gender"]').forEach(radio => {
            radio.addEventListener('click', function () {
                if (selectedGenderRadio === this) {
                    this.checked = false;
                    selectedGenderRadio = null;
                } else {
                    selectedGenderRadio = this;
                }
                applyFilter();
            });
        });

        document.querySelectorAll('input[name="category"]').forEach(radio => {
            radio.addEventListener('click', function () {
                if (selectedCategoryRadio === this) {
                    this.checked = false;
                    selectedCategoryRadio = null;
                } else {
                    selectedCategoryRadio = this;
                }
                applyFilter();
            });
        });


        


        // Xử lý sự kiện click vào sản phẩm
        function productClickHandler(e) {
            e.preventDefault();

            const productId = this.closest(".product").dataset.productId;
            if (!productId) {
                console.error("❌ Không tìm thấy ID sản phẩm.");
                return;
            }

            fetch(`/web2/pages/product.php?id=${productId}`)
                .then(response => response.text())
                .then(html => {
                const contentDiv = document.getElementById("content");
                if (contentDiv) {
                    contentDiv.innerHTML = html;
                    console.log(`✅ Đã hiển thị chi tiết sản phẩm ID ${productId}`);

                    // Gọi các hàm cần thiết sau khi load xong trang chi tiết
                    if (typeof hashButtonClickSize === "function") {
                        hashButtonClickSize();
                        initAddToCartButton();
                    }
                }
                })
                .catch(error => console.error(`❌ Lỗi khi tải sản phẩm ID ${productId}:`, error));
        }


        // Gọi fetchProducts khi trang được load
        fetchProducts(currentPage);

            // ===== GẮN SỰ KIỆN CHO TẤT CẢ PRODUCT LINKS =====
        function initProductEvents() {
            const productLinks = document.querySelectorAll(".product a");
            productLinks.forEach(link => {
                link.addEventListener("click", productClickHandler);
            });
        }
    }
    


    
})();




window.resetFilters = function(){
    console.log("🔄 Đặt lại toàn bộ bộ lọc");
        // Reset các biến toàn cục
        window.selectedGender = '';
        window.selectedCategory = '';
        window.productName='';
        window.size='';
        window.maxPrice='';
        window.minPrice='';

        document.getElementById("productName").value = '';
        document.getElementById("category").value = '';
        document.getElementById("gender").value = '';
        document.getElementById("size").value = '';
        document.getElementById("minPrice").value = '';
        document.getElementById("maxPrice").value = '';

}
