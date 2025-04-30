// ===== CART POPUP TOGGLE =====
function toggleCartPopup() {
    const cartPopup = document.getElementById('cartPopup');
    if (cartPopup) {
        cartPopup.style.display = (cartPopup.style.display === 'none' || cartPopup.style.display === '') ? 'flex' : 'none';
    }
}

// Đóng cart popup khi click ra ngoài
window.addEventListener('click', function (event) {
    const cartPopup = document.getElementById('cartPopup');
    const cartIcon = document.querySelector('.cart-icon');
    if (cartPopup && !cartPopup.contains(event.target) && event.target !== cartIcon) {
        cartPopup.style.display = 'none';
    }
});

// ===== SEARCH POPUP TOGGLE =====
function toggleSearch() {
    const searchPopup = document.getElementById("searchPopup");
    if (searchPopup) {
        searchPopup.style.display = (searchPopup.style.display === "none" || searchPopup.style.display === "") ? "block" : "none";
    }
}


window.selectButtonFlag= false;

// ===== DOMContentLoaded: Main Event Handler =====
document.addEventListener("DOMContentLoaded", function () {

    // ===== SEARCH FORM AJAX SUBMISSION =====
    const searchForm = document.getElementById("searchForm");

    if (searchForm) {
        searchForm.addEventListener("submit", function (event) {
            event.preventDefault();
    
            // Lấy dữ liệu từ form
            const name = document.getElementById("productName")?.value.trim() || '';
            const category = document.getElementById("category")?.value || '';
            const gender = document.getElementById("gender")?.value || '';
            const size = document.getElementById("size")?.value.trim() || '';
            const minPrice = document.getElementById("minPrice")?.value || '';
            const maxPrice = document.getElementById("maxPrice")?.value || '';

    
            // Tạo query string
            const queryParams = new URLSearchParams({
                name,
                category,
                gender,
                size,
                minPrice,
                maxPrice
            }).toString();
        
            // Gửi request AJAX tới shop.php
            fetch(`/web2/pages/shop.php?${queryParams}`, {
                method: "GET",
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(response => response.text())
            .then(html => {
                const contentDiv = document.getElementById("content");
                if (contentDiv) {
                    contentDiv.innerHTML = html;
                    console.log("✅ Đã cập nhật nội dung sản phẩm.");
                    window.selectButtonFlag=true;
                    initShopScript();

                    // ✅ Sau khi nội dung load xong thì đặt lại = false
                    setTimeout(() => {
                        window.selectButtonFlag = false;
                        console.log("🔁 Đã đặt lại selectButtonFlag = false");
                    }, 100); // hoặc chờ 1 chút nếu cần đảm bảo script xử lý xong
                    
                }
            })
            .catch(error => console.error("❌ Lỗi khi tải sản phẩm:", error));
        });
    }
    

    // ===== NAVIGATION AJAX LOAD =====
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
                        if(page.includes("home")){
                            fetchData();
                        }
                    }
                })
                .catch(error => console.error(`❌ Lỗi khi tải ${page}:`, error));
        });
    });



   

});
