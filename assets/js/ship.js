
function isValidName(name) {
    return name.trim().length >= 2;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^(0|\+84)[0-9]{9,10}$/;
    return phoneRegex.test(phone);
}

function isValidAddress(address) {
    return address.trim().length > 5;
}

function isValidCardInfo(name, number, expiry, cvv) {
    const cardNumberRegex = /^[0-9]{13,19}$/;
    const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
    const cvvRegex = /^[0-9]{3,4}$/;

    return (
        name.trim().length >= 2 &&
        cardNumberRegex.test(number) &&
        expiryRegex.test(expiry) &&
        cvvRegex.test(cvv)
    );
}

function validateShippingForm() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;

    if (!isValidName(name)) {
        alert("Tên không hợp lệ (ít nhất 2 ký tự).");
        return false;
    }

    if (!isValidEmail(email)) {
        alert("Email không hợp lệ.");
        return false;
    }

    if (!isValidPhone(phone)) {
        alert("Số điện thoại không hợp lệ.");
        return false;
    }

    if (!isValidAddress(address)) {
        alert("Địa chỉ quá ngắn.");
        return false;
    }

    if (paymentMethod === 'online') {
        const cardName = document.getElementById('cardName').value;
        const cardNumber = document.getElementById('cardNumber').value;
        const expiryDate = document.getElementById('expiryDate').value;
        const cvv = document.getElementById('cvv').value;

        if (!isValidCardInfo(cardName, cardNumber, expiryDate, cvv)) {
            alert("Thông tin thẻ không hợp lệ.");
            return false;
        }
    }

    return true;
}



function initShippingPage() {
    setupPaymentMethodToggle();
    setupPlaceOrderHandler();
    showCartSummary();
    setupLatestShippingCheckbox();;
}


// Hiển thị hoặc ẩn các input của thanh toán online
function setupPaymentMethodToggle() {
    document.querySelectorAll('input[name="paymentMethod"]').forEach(el => {
        el.addEventListener('change', function () {
            const isOnline = this.value === 'online';
            document.querySelectorAll('.online-payment-fields').forEach(field => {
                field.style.display = isOnline ? 'block' : 'none';
            });
        });
    });
}

// Thiết lập checkbox sử dụng địa chỉ giao hàng gần nhất
function setupLatestShippingCheckbox() {
    const checkbox = document.getElementById('useLatestShipping');
    if (!checkbox) return;

    checkbox.addEventListener('change', async () => {
        if (checkbox.checked) {
            const success = await loadLatestShippingInfo();
            if (!success) {
                checkbox.checked = false;
            }
        }
    });
}

// Gửi yêu cầu lấy địa chỉ giao hàng gần nhất từ server
async function loadLatestShippingInfo() {
    try {
        const res = await fetch('/web2/includes/get_latest_shipping.php');
        const data = await res.json();

        if (data.status === 'success' && data.shipping) {
            updateShippingForm(data.shipping);
            return true;
        } else {
            alert(data.message || 'Không tìm thấy địa chỉ giao hàng.');
            return false;
        }
    } catch (error) {
        console.error('Lỗi khi lấy địa chỉ gần nhất:', error);
        alert('Đã xảy ra lỗi khi tải địa chỉ.');
        return false;
    }
}

// Gán dữ liệu vào form
function updateShippingForm(shipping) {
    document.getElementById('name').value = shipping.shipping_name || '';
    document.getElementById('phone').value = shipping.shipping_phone || '';
    document.getElementById('address').value = shipping.shipping_address || '';
}


// Gửi yêu cầu đặt hàng
function setupPlaceOrderHandler() {
    const button = document.querySelector(".place-order");
    button.addEventListener("click", function () {

        if (validateShippingForm()) {
            showMessageDialog("Thông tin hợp lệ. Tiến hành đặt hàng...");
            // Gửi dữ liệu hoặc chuyển bước tiếp theo
            const orderData = {
                customer_id: window.user_id,
                order_date: new Date().toISOString().slice(0, 19).replace('T', ' '),
                total_amount: window.cartUser.totalPrice,
                status: 'pending    ',
                shipping_name: document.getElementById("name").value,
                shipping_phone: document.getElementById("phone").value,
                shipping_address: document.getElementById("address").value
            };


            console.log("Dữ liệu đơn hàng:", orderData);
            submitOrder(orderData);
        }
        
    });
}


async function showCartSummary() {
    const orderSummary = document.getElementById('orderSummary');
    orderSummary.innerHTML = '';

    if (!window.cartUser || window.cartUser.length === 0) {
        orderSummary.innerHTML = '<p>Giỏ hàng trống.</p>';
        return;
    }

    let totalPrice = 0;

    for (const item of window.cartUser) {
        const sizeInfo = await getProductSizeInfo(item.product_size_id);
        const productInfo = await getProductInfo(sizeInfo.product_id);
        if (!productInfo) continue;

        const itemPrice = productInfo.price * item.quantity;
        totalPrice += itemPrice;

        const itemHTML = `
            <div class="summary-item">
                <img src="${productInfo.image}" alt="${productInfo.name}">
                <p><strong>${productInfo.name}</strong></p>
                <p>Size: ${sizeInfo.size} | Số lượng: ${item.quantity}</p>
                <p>Thành tiền: ${itemPrice.toLocaleString()} đ</p>
            </div>
        `;
        orderSummary.innerHTML += itemHTML;
    }

    // Tổng tiền
    const totalHTML = `
        <div class="summary-total">
            <hr>
            <p><strong>Tổng cộng: ${totalPrice.toLocaleString()} đ</strong></p>
        </div>
    `;
    orderSummary.innerHTML += totalHTML;
}


async function submitOrder(orderData) {
    try {
        const response = await fetch("/web2/includes/place_order.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(orderData)
        });

        const text = await response.text();
        console.log(text);
        let data;
        try {
            data = JSON.parse(text);
            console.log(data);
        } catch (jsonError) {
            console.error("❌ Phản hồi không phải JSON từ place_order.php:", text);
            alert("❌ Lỗi máy chủ: không thể phân tích dữ liệu đơn hàng.");
            return;
        }

        if (data.status === "success") {
            alert("✅ Đặt hàng thành công!");
            
            // Gửi chi tiết đơn hàng
            const savedDetails = await saveOrderDetails(data.order_id);
            if (savedDetails) {
                 const stockUpdated = await updateStockAfterOrder();
                if (stockUpdated) {
                    alert("✅ Đặt hàng và cập nhật kho thành công!");
                    window.cartUser = [];
                // 👉 Tuỳ chọn: chuyển trang hoặc reload        
                // window.location.href = '/web2/thankyou.php';
                } else {
                    alert("❌ Lưu chi tiết thành công nhưng cập nhật kho thất bại!");
                }
            } else {
                alert("❌ Lỗi khi lưu chi tiết đơn hàng.");
            }
        } else {
            console.error("❌ Lỗi từ server:", data.message || "Không rõ lý do.");
            alert("❌ Lỗi đặt hàng: " + (data.message || "Không rõ nguyên nhân."));
        }
    } catch (error) {
        console.error("❌ Lỗi khi gửi đơn hàng:", error);
        alert("❌ Đã xảy ra lỗi khi gửi đơn hàng.");
    }
}




async function saveOrderDetails(order_id) {
    if (!window.cartUser || window.cartUser.length === 0) {
        console.error("❌ Giỏ hàng trống.");
        return false;
    }

    const orderDetails = window.cartUser.map(item => ({
        order_id: order_id,
        product_size_id: item.product_size_id,
        quantity: item.quantity,
        price: item.price
    }));

    console.log(orderDetails);

    try {
        const response = await fetch('/web2/includes/place_order_details.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ order_details: orderDetails })
        });

        const text = await response.text(); // đọc phản hồi thô (vì có thể không phải JSON)

        try {
            const data = JSON.parse(text); // cố gắng parse JSON

            if (data.status === 'success') {
                console.log("✅ Đã lưu order_details.");
                return true;
            } else {
                console.error("❌ Lỗi lưu order_details:", data.message || "Không rõ nguyên nhân.");
                return false;
            }
        } catch (parseError) {
            console.error("❌ Phản hồi không phải JSON:", text);
            return false;
        }
    } catch (networkError) {
        console.error("❌ Lỗi khi gửi order_details:", networkError);
        return false;
    }
}




async function updateStockAfterOrder() {
    if (!window.cartUser || window.cartUser.length === 0) {
        console.error("❌ Giỏ hàng trống.");
        return false;
    }

    const stockUpdateData = window.cartUser.map(item => ({
        product_size_id: item.product_size_id,
        quantity: item.quantity
    }));

    try {
        const res = await fetch('/web2/includes/update_stock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ items: stockUpdateData })
        });

        const text = await res.text();
        const data = JSON.parse(text);

        if (data.status === 'success') {
            console.log("✅ Cập nhật kho thành công.");
            return true;
        } else {
            console.error("❌ Lỗi cập nhật kho:", data.message);
            return false;
        }
    } catch (error) {
        console.error("❌ Lỗi khi gửi cập nhật kho:", error);
        return false;
    }
}

