// ===== CART POPUP TOGGLE =====

async function getUserIdFromSession() {
    const res = await fetch("includes/check_account.php");
    const data = await res.json();
    return data.status === 'success' ? data.user_id : null;
}

// Hiển thị/ẩn cart popup
async function toggleCartPopup() {
    
    if (window.user_id) {
        const popup = document.getElementById("cartPopup");
        popup.classList.toggle("active"); // Mở popup

        showCart(user_id); // Hiển thị giỏ hàng
        check_out_button();
    } else {
        showMessageDialog("Vui lòng đăng nhập để xem giỏ hàng.");
    }
}


// Bấm nút X để đóng
document.getElementById("closeCart").addEventListener("click", () => {
    document.getElementById("cartPopup").classList.remove("active");
});



// ===== SEARCH POPUP TOGGLE =====
function toggleSearch() {
    const searchPopup = document.getElementById("searchPopup");
    if (searchPopup) {
        searchPopup.style.display = (searchPopup.style.display === "none" || searchPopup.style.display === "") ? "block" : "none";
    }
}


window.selectButtonFlag= false;

document.addEventListener("DOMContentLoaded", function () {

  // ===== SEARCH FORM AJAX SUBMISSION =====
  const searchForm = document.getElementById("searchForm");

  if (searchForm) {
    searchForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const name = document.getElementById("productName")?.value.trim() || '';
      const category = document.getElementById("category")?.value || '';
      const gender = document.getElementById("gender")?.value || '';
      const size = document.getElementById("size")?.value.trim() || '';
      const minPrice = document.getElementById("minPrice")?.value || '';
      const maxPrice = document.getElementById("maxPrice")?.value || '';

      const queryParams = new URLSearchParams({
        name, category, gender, size, minPrice, maxPrice
      }).toString();

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
            window.selectButtonFlag = true;
            initShopScript();

            setTimeout(() => {
              window.selectButtonFlag = false;
              console.log("🔁 Đã đặt lại selectButtonFlag = false");
            }, 100);
          }
        })
        .catch(error => console.error("❌ Lỗi khi tải sản phẩm:", error));
    });
  }

  // ===== GÁN SỰ KIỆN CLICK VÀO CÁC .nav-link =====
  initNavLinkEvents();
});

// ===== GÁN SỰ KIỆN CHO TẤT CẢ NAV LINKS =====
function initNavLinkEvents() {
  const navLinks = document.querySelectorAll(".nav-link");
  navLinks.forEach(link => {
    link.addEventListener("click", navLinkHandler);
  });
}

// ===== HÀM XỬ LÝ NAVIGATION LINK =====
function navLinkHandler(e) {
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

        if (page.includes("home")) {
          fetchData();
        }

        if (page.includes("login")) {
          handleLogin();
          handleSigin();
          gannutlogin();
        }

        if (page.includes("user")) {
          fetchUser();
          fetchOrders();
          initUserPage();
          gannut();
        }
        if (page.includes("ship")) {
           document.getElementById("cartPopup").classList.remove("active");
           initShippingPage();
        }
      }
    })
    .catch(error => console.error(`❌ Lỗi khi tải ${page}:`, error));
}

// ===== HÀM HIỂN THỊ TÊN NGƯỜI DÙNG SAU ĐĂNG NHẬP =====
function showGreeting(username) {
    const chip = document.createElement("div");
    chip.id = "greeting-chip";
    chip.className = "nav-link";
    chip.dataset.page = "user.php";

    const text = document.createElement("span");
    text.className = "greeting-text";
    text.textContent = `${username}`;

    chip.appendChild(text);

    const oldLink = document.getElementById("nav-user");
    if (oldLink) {
      oldLink.replaceWith(chip);
    }

    // ✅ Gán sự kiện lại cho phần tử mới
    chip.addEventListener("click", navLinkHandler);
}

function check_out_button(){
  const button = document.getElementById("checkoutBtn");
  button.dataset.page = "ship.php";
  button.addEventListener("click", navLinkHandler);
}




//hàm khi log out sẽ quay về lại ban đầu
function restoreLoginLink() {
      const loginLink = document.createElement("a");
      loginLink.href = "";
      loginLink.className = "nav-link";
      loginLink.dataset.page = "login.php";
      loginLink.id = "nav-user";

      const icon = document.createElement("i");
      icon.className = "bx bx-user";

      loginLink.appendChild(icon);

      // Chèn lại vào vị trí cũ
      const navBar = document.querySelector(".nav-bar"); // hoặc nơi chứa nav
      const current = document.querySelector(".greeting-chip");

      if (current && navBar) {
        navBar.replaceChild(loginLink, current);
      } else {
        // Trường hợp replace thất bại, có thể dùng append
        const container = document.getElementById("nav-user-container");
        if (container) {
          container.innerHTML = "";
          container.appendChild(loginLink);
        }
      }

      icon.addEventListener("click", navLinkHandler);
}



window.cartUser = window.cartUser || []; // Chứa dữ liệu giỏ hàng toàn cục

async function showCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    cartItemsContainer.innerHTML = '';

    

    if (!window.cartUser || window.cartUser.length === 0) {
        cartItemsContainer.innerHTML = '<p>Giỏ hàng trống.</p>';
        return;
    }

    let totalPrice = 0;

    for (const item of window.cartUser) {
        const sizeInfo = await getProductSizeInfo(item.product_size_id);
        const productInfo = await getProductInfo(sizeInfo.product_id);
        if (!productInfo) continue;

        const itemPrice = productInfo.price * item.quantity;
        totalPrice += itemPrice;

        // Gán itemPrice vào phần tử trong window.cartUser
        item.price = itemPrice;

        const cartItemHTML = `
          <div class="cart-item" data-size-id="${item.product_size_id}">
              <img src="${productInfo.image}" alt="${productInfo.name}">
              <div class="cart-item-info">
                  <p><strong>${productInfo.name}</strong></p>
                  <p>Size: ${sizeInfo.size}</p>
                  <div class="quantity-control">
                      <button class="decrease">-</button>
                      <span class="quantity">${item.quantity}</span>
                      <button class="increase">+</button>
                  </div>
                  <p class="price">Giá: ${(itemPrice).toLocaleString()} đ</p>
                  <button class="remove-item">Xóa</button>
              </div>
          </div>
        `;

        cartItemsContainer.innerHTML += cartItemHTML;


    }
     
    // Gán totalPrice vào cartUser như một property bổ sung
    window.cartUser.totalPrice = totalPrice;

    const totalHTML = `
        <div class="cart-total" style="text-align: right; margin-top: 10px; font-weight: bold;">
            Tổng tiền: ${totalPrice.toLocaleString()} đ
        </div>
    `;
    

    cartItemsContainer.innerHTML += totalHTML;


    // Gắn sự kiện sau khi render
    document.querySelectorAll('.increase').forEach(button => {
        button.addEventListener('click', (e) => {
            const sizeId = e.target.closest('.cart-item').getAttribute('data-size-id');
            increaseQuantity(sizeId);
        });
    });

    document.querySelectorAll('.decrease').forEach(button => {
        button.addEventListener('click', (e) => {
            const sizeId = e.target.closest('.cart-item').getAttribute('data-size-id');
            decreaseQuantity(sizeId);
        });
    });

    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', (e) => {
            const sizeId = e.target.closest('.cart-item').getAttribute('data-size-id');
            removeItem(sizeId);
        });
    });

    console.log(window.cartUser);
}




function increaseQuantity(size_id) {
    const item = window.cartUser.find(i => i.product_size_id == size_id);
    if (item) {
        item.quantity++;
        console.log(item);
        showCart(); // Cập nhật lại giao diện
    }
}

function decreaseQuantity(size_id) {
    const item = window.cartUser.find(i => i.product_size_id == size_id);
    if (item && item.quantity > 1) {
        item.quantity--;
        console.log(item);
        showCart();
    }
}

function removeItem(size_id) {
    fetch('/web2/includes/remove_cart_item.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ product_size_id: size_id  })
    })
    .then(res => res.text())
    .then(text => {
        try {
            const data = JSON.parse(text);

            if (data.status === 'success') {
                // Xóa phần tử khỏi cartUser trên client
                window.cartUser = window.cartUser.filter(item => item.product_size_id != size_id);
                showCart();
            } else {
                console.error("❌ Lỗi khi xóa khỏi giỏ hàng:", data.message || text);
                alert(data.message);
            }
        } catch (err) {
            console.error("❌ Phản hồi không hợp lệ từ server:", text);
        }
    })
    .catch(err => {
        console.error("❌ Lỗi kết nối server:", err);
    });
}




