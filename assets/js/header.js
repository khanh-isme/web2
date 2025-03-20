



let cart = [];

    function renderCart() {
        const cartBody = document.getElementById("cartBody");
        cartBody.innerHTML = "";

        cart.forEach((item, index) => {
            const row = `
                <tr>
                    <td><img src="${item.image}" alt="${item.name}" class="product-image"></td>
                    <td>${item.name}</td>
                    <td>$${item.price}</td>
                    <td>
                        <button class="btn-decrease" onclick="changeQuantity(${index}, -1)">-</button>
                        ${item.quantity}
                        <button class="btn-increase" onclick="changeQuantity(${index}, 1)">+</button>
                    </td>
                    <td>$${(item.price * item.quantity).toFixed(2)}</td>
                    <td>
                        <button class="btn btn-delete" onclick="removeFromCart(${index})">Remove</button>
                    </td>
                </tr>
            `;
            cartBody.insertAdjacentHTML("beforeend", row);
        });

        updateTotal();
    }

    function addToCart(name, price, image) {
        const existingProduct = cart.find(item => item.name === name);
        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            cart.push({ name, price, quantity: 1, image });
        }
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function changeQuantity(index, amount) {
        if (cart[index].quantity + amount > 0) {
            cart[index].quantity += amount;
        } else {
            cart.splice(index, 1);
        }
        renderCart();
    }

    function updateTotal() {
        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        document.getElementById("totalPrice").innerText = `Total: $${total.toFixed(2)}`;
    }

    renderCart();


    //cart push up
    function toggleCartPopup() {
        const cartPopup = document.getElementById('cartPopup');
        cartPopup.style.display = cartPopup.style.display === 'none' || cartPopup.style.display === '' ? 'flex' : 'none';
    }

    function removeItem() {
        alert("Item removed (Demo Function)");
        // You can add JavaScript logic here to remove the item from the cart
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
      
    

      
// viết cái nút tăng giảm số lượng    
// Hàm tăng số lượng





  
  