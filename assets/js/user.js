// Lấy thông tin người dùng
function fetchUser() {
    fetch('/web2/includes/get_infoUser.php')
        .then(res => res.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.status === 'success') {
                    const user = data.user;
                    document.getElementById("name").textContent = user.name;
                    document.getElementById("email").textContent = user.email;
                    document.getElementById("phone").textContent = user.phone;
                    document.getElementById("address").textContent = user.address;
                    document.getElementById("status").textContent = user.status;
                } else {
                    document.getElementById("user-info").style.display = "none";
                    document.getElementById("user-error").textContent = data.message;
                }
            } catch (e) {
                console.error("Dữ liệu JSON không hợp lệ:", text);
            }
        })
        .catch(err => {
            console.error("Lỗi khi lấy thông tin người dùng:", err);
            document.getElementById("user-error").textContent = "Không thể tải thông tin người dùng.";
        });
}

// Lấy danh sách đơn hàng
function fetchOrders() {
    fetch('/web2/includes/get_orders.php')
        .then(res => res.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                const container = document.getElementById("order-list");
                container.innerHTML = "";

                if (data.status !== 'success' || !data.orders.length) {
                    container.innerHTML = `<p class="error">${data.message || "Không có đơn hàng nào."}</p>`;
                    return;
                }

                data.orders.forEach(order => {
                    const orderElement = document.createElement("div");
                    orderElement.classList.add("order-item");
                    orderElement.innerHTML = `
                        <div class="order-item-element">
                            <h4>Đơn hàng #${order.order_id}</h4>
                            <p>Ngày đặt: ${order.order_date}</p>
                            <p>Tổng tiền: ${order.total_amount} đ</p>
                            <p>Trạng thái: ${order.status}</p>
                            <p>Người nhận: ${order.shipping_name}</p>
                            <p>SĐT: ${order.shipping_phone}</p>
                            <p>Địa chỉ: ${order.shipping_address}</p>
                        </div>
                        <hr>
                        <div class="order-actions" onclick="showOrderDetails(${order.order_id})">
                            <i class='bx bx-edit-alt' ></i>
                        </div>
                    `;
                    container.appendChild(orderElement);
                });
            } catch (e) {
                console.error("Dữ liệu không phải JSON:", text);
            }
        })
        .catch(err => {
            console.error("Lỗi khi fetch đơn hàng:", err);
        });
}

// ======= Các hàm hỗ trợ API =======
async function getOrderDetails(order_id) {
    const res = await fetch(`includes/get_order_details.php?order_id=${order_id}`);
    const data = await res.json();
    return data.status === 'success' ? data.details : [];
}



// ======= Hiển thị chi tiết đơn hàng =======
async function showOrderDetails(order_id) {
    const detailsDialog = document.querySelector(".order-details-dialog");
    detailsDialog.classList.toggle("active");

    const dialog = document.getElementById('order-dialog');
    dialog.innerHTML = ''; // clear previous
    dialog.style.display = 'block';

    const details = await getOrderDetails(order_id);
    console.log(details);
    if (!details.length) {
        dialog.innerHTML = '<p>Không có chi tiết đơn hàng.</p>';
        return;
    }

    let html = `<h3>Chi tiết đơn hàng #${order_id}</h3><ul style="list-style:none;padding:0;">`;

    let totalQuantity = 0;
    let totalPrice = 0;

    for (const item of details) {
        console.log(item.product_size_id);

        const sizeInfo = await getProductSizeInfo(item.product_size_id);
        console.log(sizeInfo);
        if (!sizeInfo) continue;

        const product = await getProductInfo(sizeInfo.product_id);
        console.log(product);
        if (!product) continue;

        const itemTotalPrice = item.quantity * item.price;
        totalQuantity += item.quantity;
        totalPrice += itemTotalPrice;

        html += `
            <li style="margin-bottom: 15px;">
                <img src="${product.image}" alt="${product.name}" style="width:100px;height:auto;">
                <br>
                <strong>${product.name}</strong><br>
                Size: ${sizeInfo.size}<br>
                Số lượng: ${item.quantity}<br>
                Giá: ${Number(item.price).toLocaleString()} đ<br>
                Thành tiền: ${Number(itemTotalPrice).toLocaleString()} đ
                <hr>
            </li>
        `;
    }

    html += `</ul>
        <div style="margin-top: 20px; font-weight: bold;">
            Tổng số lượng: ${totalQuantity} <br>
            Tổng tiền: ${Number(totalPrice).toLocaleString()} đ
        </div>
    `;

    dialog.innerHTML = html;
}



// Bấm nút X để đóng
function setupCloseOrderDetails() {
    const closeBtn = document.getElementById("closeDetails");
    const dialog = document.querySelector(".order-details-dialog");

    if (closeBtn && dialog) {
        closeBtn.addEventListener("click", () => {
            dialog.classList.remove("active");
        });
    } else {
        console.warn("Không tìm thấy #closeDetails hoặc .order-details-dialog trong DOM.");
    }
}


function initUserPage() {
  initEditUserPopup();      // Gán sự kiện cho nút chỉnh sửa
  initUserFormHandler();    // Gửi form chỉnh sửa
}

function initEditUserPopup() {
  const editBtn = document.getElementById("edit-user-btn");
  const overlay = document.getElementById("edit-user-overlay");
  const popup = document.getElementById("edit-user-popup");
  const cancelBtn = document.getElementById("cancel-edit");

  if (!editBtn || !overlay || !popup || !cancelBtn) return;

  editBtn.addEventListener("click", () => {
    document.getElementById("edit-name").value = document.getElementById("name").textContent;
    document.getElementById("edit-email").value = document.getElementById("email").textContent;
    document.getElementById("edit-phone").value = document.getElementById("phone").textContent;
    document.getElementById("edit-address").value = document.getElementById("address").textContent;

    overlay.style.display = "block";
    popup.style.display = "block";
  });

  cancelBtn.addEventListener("click", closeEditPopup);
  overlay.addEventListener("click", closeEditPopup);
}

function closeEditPopup() {
  const overlay = document.getElementById("edit-user-overlay");
  const popup = document.getElementById("edit-user-popup");
  if (overlay && popup) {
    overlay.style.display = "none";
    popup.style.display = "none";
  }
}

function initUserFormHandler() {
  const form = document.getElementById("edit-user-form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const updatedInfo = {
      name: document.getElementById("edit-name").value,
      email: document.getElementById("edit-email").value,
      phone: document.getElementById("edit-phone").value,
      address: document.getElementById("edit-address").value,
      user_id: window.user_id || null
    };

    updateUserInfo(updatedInfo);
  });
}

function updateUserInfo(updatedInfo) {
  fetch("/web2/includes/update_user_info.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(updatedInfo)
  })
  .then(response => response.text()) // Lấy thô nội dung trả về
  .then(text => {
    try {
      const data = JSON.parse(text); // Cố parse JSON
      if (data.status === "success") {
        showMessageDialog("✅ Cập nhật thông tin thành công!");
        fetchUser(); // Gọi lại để load thông tin mới
      } else {
        showMessageDialog("❌ " + (data.message || "Cập nhật thất bại!"));
      }
    } catch (error) {
      console.error("❌ Không parse được JSON:", text);
      showMessageDialog("❌ Lỗi hệ thống: dữ liệu phản hồi không hợp lệ.");
    }
  })
  .catch(error => {
    console.error("❌ Lỗi khi gửi yêu cầu cập nhật:", error);
    showMessageDialog("❌ Không thể kết nối đến máy chủ.");
  });
}

function gannut(){
  document.getElementById('logout-btn').addEventListener('click', handleLogout);

}

function handleLogout() {
    saveCartToServer();
    fetch('/web2/includes/logout.php', {
        method: 'POST'
    })
    .then(res => res.text())
    .then(text => {
        console.log("🔁 Phản hồi thô từ server:", text);
        let data;

        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error("❌ Lỗi phân tích JSON:", e);
            showMessageDialog("❌ Phản hồi không hợp lệ từ máy chủ.");
            return;
        }

        if (data.status === 'success') {
            
            showMessageDialog(data.message); // hoặc hiển thị bằng modal
           window.location.href = '/web2/index.php';
        } else {
            alert("❌ Đăng xuất thất bại: " + (data.message || 'Không rõ nguyên nhân.'));
        }
    })
    .catch(error => {
        console.error("❌ Lỗi khi gửi yêu cầu đăng xuất:", error);
        alert("❌ Không thể kết nối tới máy chủ.");
    });
}

function saveCartToServer() {
    if (!window.cartUser || window.cartUser.length === 0) {
        console.log('Giỏ hàng trống, không gửi dữ liệu.');
        return;
    }

    fetch('/web2/includes/save_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cart: window.cartUser })
    })
    .then(res => res.text())  // Nhận response dạng text
    .then(responseText => {
        try {
            const data = JSON.parse(responseText);
            if (data.status === 'success') {
                console.log('Lưu giỏ hàng thành công!');
            } else {
                console.error('Lỗi khi lưu giỏ hàng:', data.message);
            }
        } catch (error) {
            console.error('Lỗi parse JSON:', error);
            console.log('Response text:', responseText);
        }
    })
    .catch(err => {
        console.error('Lỗi fetch:', err);
    });
}


