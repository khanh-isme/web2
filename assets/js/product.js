function selectButton(button) {
    // Bỏ class active khỏi tất cả các nút
    document.querySelectorAll('.button').forEach(btn => btn.classList.remove('active'));

    // Thêm class active cho nút được chọn
    button.classList.add('active');
    // Trả về thông tin size đã chọn dưới dạng object
    return {
        size: button.dataset.size,
        sizeId: parseInt(button.dataset.sizeId),
        productId: parseInt(button.dataset.productId)
    };
}


function changeQuantity(change) {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) || 1;
    value += change;
    if (value < 1) value = 1;
    input.value = value;
}

let selectedSizeInfo = null;
function hashButtonClickSize(){

    document.querySelectorAll('.button').forEach(button => {
        button.addEventListener('click', function () {
            selectedSizeInfo = selectButton(this);
            console.log("Size đã chọn:", selectedSizeInfo);
        });
    });
}



// Xử lý khi nhấn "Add to Cart"
function initAddToCartButton() {
    
    const addToCartBtn = document.querySelector('.add-to-bag');


    if (!addToCartBtn) return;

    addToCartBtn.addEventListener('click', () => {
        if (!selectedSizeInfo) {
            showMessageDialog("Vui lòng chọn size trước khi thêm vào giỏ hàng.");
            return;
        }
        
        if(!window.user_id){
            showMessageDialog("Vui lòng đăng nhập ");
            return;
        }

        const quantity = parseInt(document.getElementById('quantity')?.value || 1);

        const cartItem = {
            product_id: selectedSizeInfo.productId,
            product_size_id: selectedSizeInfo.sizeId,
            size: selectedSizeInfo.size,
            quantity: quantity
        };

        window.cartUser = window.cartUser || [];

        // Kiểm tra nếu sản ph ẩm + size đã tồn tại thì tăng số lượng
        const existing = cartUser.find(item => item.product_size_id === cartItem.product_size_id);
        if (existing) {
            existing.quantity += quantity;
        } else {
            cartUser.push(cartItem);
        }

        console.log("Giỏ hàng hiện tại:", cartUser);
        toggleCartPopup();
    });
}
