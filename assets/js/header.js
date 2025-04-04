



    //cart push up
    function toggleCartPopup() {
        const cartPopup = document.getElementById('cartPopup');
        cartPopup.style.display = cartPopup.style.display === 'none' || cartPopup.style.display === '' ? 'flex' : 'none';
    }

  
    // Close cart popup when clicking outside of it
    window.onclick = function(event) {
        const cartPopup = document.getElementById('cartPopup');
        const cartIcon = document.querySelector('.cart-icon');

        // Check if click is outside the cart popup and not on the cart icon
        if (event.target !== cartPopup && event.target !== cartIcon && !cartPopup.contains(event.target)) {
            cartPopup.style.display = 'none';
        }
    }


    function toggleSearch() {
        const searchContainer = document.getElementById("searchPopup");
        searchContainer.style.display = searchContainer.style.display === "none" || searchContainer.style.display === "" ? "block" : "none";
    }


    document.addEventListener("DOMContentLoaded", function () {
        const searchForm = document.getElementById("searchForm");
    
        searchForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Ngăn chặn reload trang
    
            const productName = document.getElementById("productName").value.trim();
    
    
    
            // Gửi AJAX đến shop.php
            fetch(`/web2/pages/shop.php?name=${productName}`, {
                method: "GET",
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(response => response.text())
            .then(html => {
                const contentDiv = document.getElementById("content");
                if (contentDiv) {
                    contentDiv.innerHTML = html;
                    
                    console.log("✅ Đã cập nhật nội dung sản phẩm.");
            
                    const scriptSrc = "/web2/assets/js/shop.js";
                    //kiểm tra nếu nó đã có DOM rồi thì không thêm nữa
                    if (!document.querySelector(`script[src="${scriptSrc}"]`)) {
                        const script = document.createElement("script");
                        script.src = scriptSrc;
                        script.onload = () => {
                            console.log("✅ Shop.js đã tải lại.");
                            if (typeof initShopScript === "function") {
                                initShopScript();
                            } else {
                                console.error("❌ initShopScript không tồn tại sau khi tải lại script.");
                            }
                        };
                        document.body.appendChild(script);
                    } else {
                        console.log("⚠️ Shop.js đã tồn tại, không tải lại.");
                        if (typeof initShopScript === "function") {
                            initShopScript();
                        }
                    }
                }    
            })
            .catch(error => console.error("❌ Lỗi khi tải sản phẩm:", error));
            
        });
    });
    
    
      
    

      





  