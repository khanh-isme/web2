function Supplier_PageEvent() {
    const btn_switch_s = document.getElementById("sr-switch-supplier");
    const btn_switch_r = document.getElementById("sr-switch-receipt");
    const receiptDetail = document.getElementById("receipt-details");
    const supplierDetail = document.getElementById("supplier-details");

    btn_switch_s.addEventListener("click", () => {
        supplierDetail.style.display = "grid";
        receiptDetail.style.display = "none";
        btn_switch_s.classList.add("active");
        btn_switch_r.classList.remove("active");
    });

    if (btn_switch_r)
        btn_switch_r.addEventListener("click", () => {
            receiptDetail.style.display = "grid";
            supplierDetail.style.display = "none";
            btn_switch_r.classList.add("active");
            btn_switch_s.classList.remove("active");
        });
    function loadSuppliers() {
        fetch("includes/right_content/suppliers/supplierAction.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "action=get_all"
        })
            .then(response => response.text())
            .then(responseData => {
                try {
                    let data = JSON.parse(responseData);
                    if (data.success != false) {
                        const tbody = document.getElementById("supplier-list");
                        tbody.innerHTML = "";
                        Promise.all([checkPermission('EDIT_SUPPLIER'), checkPermission('DELETE_SUPPLIER')])
                            .then(([canEdit, canDetele]) => {
                                document.getElementById("total-suppliers").textContent = data.length;
                                if (data.length === 0) {
                                    tbody.innerHTML = '<tr><td colspan="6">Không có nhà cung cấp nào.</td></tr>';
                                    return;
                                }
                                data.forEach(supplier => {
                                    const row = document.createElement("tr");
                                    row.dataset.id = supplier.id;
                                    row.innerHTML = `
                                <td>${supplier.id.toString()}</td>
                                <td>${supplier.name}</td>
                                <td>${supplier.contact}</td>
                                <td>${supplier.email}</td>
                                <td>${supplier.phone}</td>
                                <td>
                                    <div class="sr-table-action">
                                        ${canEdit ? `<button class="edit-supplier-btn"><i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa</button>` : ''}
                                        ${canDetele ? `<button class="delete-supplier-btn"><i class="fa-solid fa-trash"></i> Xóa</button>` : ''}
                                    </div>
                                </td>
                            `;
                                    tbody.appendChild(row);
                                });
                            });
                    }
                    else {
                        console.error(data.error);
                    }
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(error => {
                console.error("Error loading suppliers:", error);
                const tbody = document.getElementById("supplier-list");
                tbody.innerHTML = `<tr><td colspan="6">Lỗi khi tải dữ liệu</td></tr>`;
            });
    }
    loadSuppliers();

    document.getElementById("supplier-search").addEventListener("input", function () {
        const keyword = this.value.trim();
        const suggestionBox = document.getElementById("suggestions-list");

        if (keyword.length === 0) {
            suggestionBox.innerHTML = "";
            return;
        }

        fetch(`includes/right_content/suppliers/Search.php?action=supplier&keyword=${encodeURIComponent(keyword)}`)
            .then(response => response.text())
            .then(responseData => {
                try {
                    let data = JSON.parse(responseData);
                    suggestionBox.innerHTML = "";
                    if (data.success && data.results.length > 0) {
                        data.results.forEach(item => {
                            const li = document.createElement("li");
                            li.textContent = `${item.id} - ${item.name}`;
                            li.addEventListener("click", () => {
                                document.getElementById("supplier-search").value = `${item.name}`;
                                suggestionBox.innerHTML = "";
                                highlightSupplierRow(item.id);
                            });
                            suggestionBox.appendChild(li);
                        });
                    }
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(err => {
                console.error("Lỗi tìm kiếm:", err);
            });
    });

    function highlightSupplierRow(supplierId) {
        const row = document.querySelector(`#supplier-list tr[data-id="${supplierId}"]`);
        if (row) {
            row.scrollIntoView({ behavior: "smooth", block: "center" });
            row.style.backgroundColor = "#ffff99";
        }
    }
    const supplierAddCtn = document.querySelector(".supplier-add-ctn");
    const addSupplierBtn = document.getElementById("add-supplier-btn");
    const closeSupplierFormBtn = document.querySelector(".close-supplier-form");
    const supplierAddForm = document.getElementById("supplier-add-form");

    if (addSupplierBtn) {
        addSupplierBtn.addEventListener("click", () => {
            supplierAddForm.reset();
            supplierAddCtn.style.display = "flex";
        });
    }

    if (document.getElementById('supplier-add-ctn'))
        document.getElementById('supplier-add-ctn').addEventListener("click", (e) => {
            const popupContainer = document.getElementById('supplier-add-ctn');
            const popupContent = document.getElementById('supplier-form-container');

            if (popupContainer && popupContainer.style.display !== 'none') {
                if (!popupContent.contains(e.target)) {
                    popupContainer.style.display = 'none';
                }
            }
        });
    if (document.getElementById("supplier-add-form"))
        document.getElementById("supplier-add-form").addEventListener("submit", (e) => {
            e.preventDefault();

            const emailInput = document.getElementById("add-email");
            const phoneInput = document.getElementById("add-phone");
            const emailError = document.getElementById("email-error");
            const phoneError = document.getElementById("phone-error");
            let valid = true;

            if (validateEmail(emailInput) === false) {
                emailError.style.display = "inline";
                valid = false;
            } else {
                emailError.style.display = "none";
            }

            if (validatePhone(phoneInput) === false) {
                phoneError.style.display = "inline";
                valid = false;
            } else {
                phoneError.style.display = "none";
            }

            if (!valid) {
                return;
            }

            const formData = new FormData(e.target);
            formData.append("action", "add");

            fetch("includes/right_content/suppliers/supplierAction.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        if (data.success) {
                            alert("Thêm nhà cung cấp thành công!");
                            document.getElementById("supplier-add-form").reset();
                            document.querySelector(".supplier-add-ctn").style.display = "none";
                            loadSuppliers();
                            document.getElementById("total-suppliers").textContent = parseInt(document.getElementById("total-suppliers").textContent) + 1;
                        } else {
                            alert(data.error || "Thêm nhà cung cấp thất bại!");
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    console.error("Error adding supplier:", error);
                    alert("Có lỗi xảy ra khi thêm nhà cung cấp!");
                });
        });

    if (closeSupplierFormBtn)
        closeSupplierFormBtn.addEventListener("click", () => {
            supplierAddCtn.style.display = "none";
            supplierAddForm.reset();
        });
    document.getElementById("supplier-list").addEventListener("click", (e) => {
        if (e.target.closest(".edit-supplier-btn")) {
            const row = e.target.closest("tr");
            const supplierId = row.dataset.id;

            fetch("includes/right_content/suppliers/supplierAction.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=get_supplier&id=${encodeURIComponent(supplierId)}`
            })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    document.getElementById("modify-id").value = data.id;
                    document.getElementById("modify-ten").value = data.name;
                    document.getElementById("modify-contact").value = data.contact || "";
                    document.getElementById("modify-email").value = data.email || "";
                    document.getElementById("modify-phone").value = data.phone || "";
                    document.getElementById("modify-address").value = data.address || "";
                    document.getElementById("form-mode").value = "edit";
                    document.getElementById("edit-id").value = supplierId;
                    document.querySelector(".supplier-modify-ctn").style.display = "flex";
                })
                .catch(error => console.error("Error fetching supplier:", error));
        }

        if (e.target.closest(".delete-supplier-btn")) {
            const row = e.target.closest("tr");
            const supplierId = row.dataset.id;

            if (confirm("Bạn có chắc muốn xóa nhà cung cấp này?")) {
                fetch("includes/right_content/suppliers/supplierAction.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `action=delete&id=${encodeURIComponent(supplierId)}`
                })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            loadSuppliers();
                            document.getElementById("total-suppliers").textContent = parseInt(document.getElementById("total-suppliers").textContent) - 1;
                        } else {
                            alert(data.error || "Xóa nhà cung cấp thất bại!");
                        }
                    })
                    .catch(error => console.error("Error deleting supplier:", error));
            }
        }
    });
    if (document.getElementById("supplier-modify-form"))
        document.getElementById("supplier-modify-form").addEventListener("submit", (e) => {
            e.preventDefault();

            const emailInput = document.getElementById("modify-email");
            const phoneInput = document.getElementById("modify-phone");
            const emailError = document.getElementById("emailm-error");
            const phoneError = document.getElementById("phonem-error");
            let valid = true;

            if (validateEmail(emailInput) === false) {
                emailError.style.display = "inline";
                valid = false;
            } else {
                emailError.style.display = "none";
            }

            if (validatePhone(phoneInput) === false) {
                phoneError.style.display = "inline";
                valid = false;
            } else {
                phoneError.style.display = "none";
            }

            if (!valid) {
                return;
            }

            const formData = new FormData(e.target);
            const mode = formData.get("form-mode");
            formData.append("action", mode === "edit" ? "update" : "add");
            formData.append("id", formData.get("edit-id"));

            fetch("includes/right_content/suppliers/supplierAction.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        if (data.success) {
                            alert("Cập nhật nhà cung cấp thành công!");
                            document.querySelector(".supplier-modify-ctn").style.display = "none";
                            loadSuppliers();
                        } else {
                            alert(data.error || "Cập nhật nhà cung cấp thất bại!");
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    console.error("Error updating supplier:", error);
                    alert("Có lỗi xảy ra khi cập nhật nhà cung cấp!");
                });
        });

    if (document.querySelector(".close-supplier-modify-form"))
        document.querySelector(".close-supplier-modify-form").addEventListener("click", () => {
            document.querySelector(".supplier-modify-ctn").style.display = "none";
        });
    const openAddReceiptFormBtn = document.getElementById('add-receipt-btn');
    const closeAddReceiptFormBtn = document.getElementById('close-receipt-main-form');
    const addReceiptForm = document.getElementById('receipt-add-form');
    const openDetailAddReceiptFormBtn = document.getElementById('open-receipt-detail-form-btn');
    const detailAddReceiptForm = document.querySelector('.receipt-detail-form-ctn');
    const closeDetailAddReceiptFormBtn = document.querySelector('.close-receipt-detail-form');
    const supplierSearchInput = document.getElementById('supplier-add-search');
    const suggestionsList = document.getElementById('suggestions-add-list');
    const discountPercentInput = document.getElementById('percent');
    const receiptProductTableBody = document.getElementById('receipt-product-rows');
    const addRowBtn = document.querySelector('.add-row-btn');
    const submitBtn = document.querySelector('.submit-btn');
    const totalAmountSpan = document.getElementById('total-amount');
    const receiptListBody = document.getElementById('receipt-list');
    const detailFormContainer = document.querySelector('.receipt-detail-form-ctn');
    const closeDetailFormBtn = document.querySelector('.close-receipt-detail-form');
    const receiptInfoDiv = document.querySelector('.receipt-info');
    const receiptProductRows = document.getElementById('receipt-product-rows');

    let selectedSupplierId = null;

    if (addReceiptForm) {
        openAddReceiptFormBtn.addEventListener('click', () => {
            addReceiptForm.style.display = 'flex';
        });

        closeAddReceiptFormBtn.addEventListener('click', () => {
            addReceiptForm.style.display = 'none';
            document.querySelector('.receipt-detail-form-container').reset();
        });
    }

    if (detailAddReceiptForm) {
        openDetailAddReceiptFormBtn.addEventListener('click', () => {
            if (!selectedSupplierId) {
                alert('Vui lòng chọn nhà cung cấp trước!');
                return;
            }
            detailAddReceiptForm.style.display = 'flex';
            addReceiptForm.style.display = 'none';
            updateReceiptInfo();
        });

        closeDetailAddReceiptFormBtn.addEventListener('click', () => {
            detailFormContainer.style.display = 'none';
            addRowBtn.style.display = 'inline-block';
            submitBtn.style.display = 'inline-block';
            document.querySelector('.receipt-detail-form-container').reset();
            receiptProductTableBody.innerHTML = '';
            addRowBtn.click();
            totalAmountSpan.textContent = "";
        });
    }
    if (supplierSearchInput) {
        supplierSearchInput.addEventListener('input', debounce(() => {
            const keyword = supplierSearchInput.value.trim();
            if (keyword.length < 2) {
                suggestionsList.innerHTML = '';
                return;
            }

            fetch(`includes/right_content/suppliers/Search.php?action=supplier&keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        suggestionsList.innerHTML = '';
                        if (data.success && data.results.length > 0) {
                            data.results.forEach(supplier => {
                                const li = document.createElement('li');
                                li.textContent = `${supplier.name} (ID: ${supplier.id})`;
                                li.addEventListener('click', () => {
                                    selectedSupplierId = supplier.id;
                                    supplierSearchInput.value = supplier.name;
                                    suggestionsList.innerHTML = '';
                                });
                                suggestionsList.appendChild(li);
                            });
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => console.error('Lỗi tìm kiếm nhà cung cấp:', error));
        }, 300));
    }

    function setupProductSearch(row) {
        const productSearchInput = row.querySelector('.product-search') || row.querySelector('#product-search');
        const suggestionsList = row.querySelector('.suggestions-product-list');
        const sizesWrapper = row.querySelector('.sizes-wrapper');

        if (!productSearchInput) {
            console.error('Không tìm thấy input tìm kiếm sản phẩm trong hàng:', row);
            return;
        }

        productSearchInput.addEventListener('input', debounce(() => {
            const keyword = productSearchInput.value.trim();
            if (keyword.length < 2) {
                suggestionsList.innerHTML = '';
                return;
            }

            fetch(`includes/right_content/suppliers/Search.php?action=product&keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        suggestionsList.innerHTML = '';
                        if (data.success && data.results.length > 0) {
                            data.results.forEach(product => {
                                const li = document.createElement('li');
                                li.textContent = `${product.name} (ID: ${product.id})`;
                                li.addEventListener('click', () => {
                                    productSearchInput.value = product.name;
                                    productSearchInput.dataset.productId = product.id;
                                    suggestionsList.innerHTML = '';
                                    loadProductSizes(product.id, sizesWrapper, row);
                                });
                                suggestionsList.appendChild(li);
                            });
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => console.error('Lỗi tìm kiếm sản phẩm:', error));
        }, 300));
    }
    function loadProductSizes(productId, sizesWrapper, row) {
        fetch(`includes/right_content/suppliers/Search.php?action=sizes&product_id=${productId}`)
            .then(response => response.text())
            .then(responseData => {
                try {
                    let data = JSON.parse(responseData);
                    sizesWrapper.innerHTML = '';
                    if (data.success && data.results.length > 0) {
                        data.results.forEach(size => {
                            addSizeInput(sizesWrapper, size.id, size.size, row);
                        });
                    } else {
                        addSizeInput(sizesWrapper, null, '', row);
                    }
                    updateRowTotals(row);
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(error => console.error('Lỗi tải kích thước:', error));
    }
    function addSizeInput(sizesWrapper, productSizeId, sizeValue, row) {
        const sizeGroup = document.createElement('div');
        sizeGroup.className = 'size-input-group';
        const rowIndex = Array.from(receiptProductTableBody.children).indexOf(row);
        sizeGroup.innerHTML = `
        <input type="hidden" name="product_size_id[${rowIndex}][]" value="${productSizeId || ''}">
        <input type="text" name="size[${rowIndex}][]" placeholder="Size" value="${sizeValue || ''}">
        <input type="number" name="quantity[${rowIndex}][]" placeholder="Số lượng" min="0" value="0">
        <button type="button" class="remove-size-btn">❌</button>
    `;
        sizesWrapper.appendChild(sizeGroup);
        sizeGroup.querySelector('input[name^="quantity"]').addEventListener('input', () => updateRowTotals(row));
        sizeGroup.querySelector('.remove-size-btn').addEventListener('click', () => {
            sizeGroup.remove();
            updateRowTotals(row);
        });
    }
    if (addRowBtn) {
        addRowBtn.addEventListener('click', () => {
            const currentRowIndex = receiptProductTableBody.children.length;
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td class="stt">${currentRowIndex + 1}</td>
            <td>
                <input type="text" id="product-search" class="idproduct-search" placeholder="Nhập tên sản phẩm..." data-product-id="">
                <ul class="suggestions-product-list"></ul>
            </td>
            <td>
                <div class="sizes-wrapper">
                    <div class="size-input-group">
                        <input type="hidden" name="product_size_id[${currentRowIndex}][]" value="">
                        <input type="text" name="size[${currentRowIndex}][]" placeholder="Size">
                        <input type="number" name="quantity[${currentRowIndex}][]" placeholder="Số lượng" min="0" value="0">
                        <button type="button" class="remove-size-btn">❌</button>
                    </div>
                </div>
                <button type="button" class="add-size-btn">+ Thêm size</button>
            </td>
            <td><input type="number" name="price[${currentRowIndex}]" class="price" value="0" min="0" step="any"></td>
            <td><span class="sell-price">0</span> đ</td>
            <td><span class="total-price">0</span> đ</td>
            <td><button type="button" class="remove-row-btn">🗑️</button></td>
        `;
            receiptProductTableBody.appendChild(newRow);
            setupProductSearch(newRow);
            newRow.querySelector('.add-size-btn').addEventListener('click', () => {
                addSizeInput(newRow.querySelector('.sizes-wrapper'), null, '', newRow);
            });
            newRow.querySelector('.price').addEventListener('input', () => updateRowTotals(newRow));
            newRow.querySelector('.remove-row-btn').addEventListener('click', () => {
                const rows = receiptProductTableBody.querySelectorAll('tr');
                if (rows.length <= 1) {
                    alert("Không thể xóa hàng cuối cùng!");
                    return;
                }
                newRow.remove();
                updateRowNumbers();
                updateTotalAmount();
            });

            updateRowNumbers();
        });
    }
    function updateRowNumbers() {
        const rows = receiptProductTableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.querySelector('.stt').textContent = index + 1;
            const sizeInputs = row.querySelectorAll('input[name^="size"]');
            const quantityInputs = row.querySelectorAll('input[name^="quantity"]');
            const productSizeIdInputs = row.querySelectorAll('input[name^="product_size_id"]');
            const priceInput = row.querySelector('input[name^="price"]');

            sizeInputs.forEach((input, i) => {
                input.name = `size[${index}][]`;
            });
            quantityInputs.forEach((input, i) => {
                input.name = `quantity[${index}][]`;
            });
            productSizeIdInputs.forEach((input, i) => {
                input.name = `product_size_id[${index}][]`;
            });
            if (priceInput) {
                priceInput.name = `price[${index}]`;
            }
        });
    }
    function updateRowTotals(row) {
        const quantities = Array.from(row.querySelectorAll('input[name^="quantity"]')).map(input => parseInt(input.value) || 0);
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const discountPercent = parseFloat(document.getElementById('percent').value) || 0;
        const total = quantities.reduce((sum, qty) => sum + qty, 0) * price;
        const sellPrice = price * (1 + discountPercent / 100);

        row.querySelector('.total-price').textContent = total.toLocaleString('vi-VN') + ' đ';
        row.querySelector('.sell-price').textContent = sellPrice.toLocaleString('vi-VN') + ' đ';
        updateTotalAmount();
    }
    function updateTotalAmount() {
        let total = 0;
        document.querySelectorAll('#receipt-product-rows tr').forEach(row => {
            const totalPrice = parseFloat(row.querySelector('.total-price').textContent.replace(/[^\d.-]/g, '')) || 0;
            total += totalPrice;
        });
        totalAmountSpan.textContent = total.toLocaleString('vi-VN') + ' đ';
    }
    if (document.querySelector(".receipt-detail-form-container")) {
        document.querySelector(".receipt-detail-form-container").addEventListener("submit", (e) => {
            e.preventDefault();

            const supplierId = selectedSupplierId;
            if (!supplierId) {
                alert("Vui lòng chọn nhà cung cấp!");
                return;
            }
            const discountPercent = parseFloat(document.getElementById("percent").value) || 0;
            const notes = "Phiếu nhập từ form";

            const details = [];
            const rows = document.querySelectorAll("#receipt-product-rows tr");
            rows.forEach((row, index) => {
                const productId = row.querySelector(".idproduct-search").dataset.productId || 0;
                const price = parseFloat(row.querySelector(".price").value) || 0;
                const sellPrice = parseFloat(row.querySelector(".sell-price").textContent.replace(/[^\d.-]/g, '')) || 0;
                const sizes = row.querySelectorAll('input[name^="size"]');
                const quantities = row.querySelectorAll('input[name^="quantity"]');

                sizes.forEach((size, sizeIndex) => {
                    const quantity = parseInt(quantities[sizeIndex].value) || 0;
                    if (quantity > 0) {
                        details.push({
                            product_id: parseInt(productId),
                            size: size.value,
                            quantity: quantity,
                            price: price,
                            sell_price: sellPrice
                        });
                    }
                });
            });

            const data = {
                receipt: {
                    supplier_id: supplierId,
                    discount_percent: discountPercent,
                    notes: notes
                },
                details: details
            };

            fetch("includes/right_content/suppliers/addReceipt.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let result = JSON.parse(responseData);
                        if (result.success) {
                            alert("Thêm phiếu nhập thành công! Receipt ID: " + result.receipt_id);
                            document.querySelector(".receipt-detail-form-ctn").style.display = "none";
                            document.getElementById('receipt-add-form').style.display = 'none';
                            document.querySelector('.receipt-detail-form-container').reset();
                            receiptProductTableBody.innerHTML = '';
                            addRowBtn.click();
                            loadReceipts();
                        } else {
                            alert("Lỗi: " + result.error);
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Có lỗi xảy ra khi thêm phiếu nhập!");
                });
        });
    }
    function updateReceiptInfo() {
        const receiptInfo = document.querySelector('.receipt-info');
        receiptInfo.innerHTML = `
        <div><strong>Trạng thái:</strong> Chưa hoàn thành</div>
        <div><strong>Nhà cung cấp:</strong> ${supplierSearchInput.value || 'Chưa chọn'}</div>
        <div><strong>Nhân viên: ${document.getElementById('admin-username').textContent}</strong></div>
        <div><strong>Chiết khấu:</strong> ${discountPercentInput.value || 0}%</div>
        <div><strong>Ngày tạo phiếu:</strong> ${new Date().toLocaleDateString('vi-VN')}</div>
    `;
    }
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    if (receiptProductTableBody) {

        const firstRow = receiptProductTableBody.querySelector('tr');
        setupProductSearch(firstRow);
        firstRow.querySelector('.add-size-btn').addEventListener('click', () => {
            addSizeInput(firstRow.querySelector('.sizes-wrapper'), null, '', firstRow);
        });
        firstRow.querySelector('.price').addEventListener('input', () => updateRowTotals(firstRow));
        firstRow.querySelector('.remove-row-btn').addEventListener('click', () => {
            const rows = receiptProductTableBody.querySelectorAll('tr');
            if (rows.length <= 1) {
                alert("Không thể xóa hàng cuối cùng!");
                return;
            }
            firstRow.remove();
            updateRowNumbers();
            updateTotalAmount();
        });
    }
    function loadReceipts() {
        fetch('includes/right_content/suppliers/receipts.php?action=receipts')
            .then(response => response.text())
            .then(responseData => {
                try {
                    let data = JSON.parse(responseData);
                    if (data.success) {
                        document.getElementById("total-receipts").textContent = data.results.length;
                        document.getElementById("total-cost").textContent = data.total_cost.toLocaleString('vi-VN') + ' đ';
                        receiptListBody.innerHTML = '';
                        data.results.forEach(receipt => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                            <td>RC${String(receipt.id).padStart(3, '0')}</td>
                            <td>${new Date(receipt.receipt_date).toLocaleDateString('vi-VN')}</td>
                            <td>${parseFloat(receipt.total_amount).toLocaleString('vi-VN')} đ</td>
                            <td>${receipt.supplier_name}</td>
                            <td>
                                <div class="sr-table-action">
                                    <button class="view-receipt-btn" data-receipt-id="${receipt.id}">Chi tiết</button>
                                </div>
                            </td>
                        `;
                            receiptListBody.appendChild(row);
                        });
                        document.querySelectorAll('.view-receipt-btn').forEach(btn => {
                            btn.addEventListener('click', () => {
                                const receiptId = btn.dataset.receiptId;
                                showReceiptDetails(receiptId);
                            });
                        });
                    } else {
                        console.error('Lỗi lấy danh sách phiếu nhập:', data.error);
                    }
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(error => console.error('Lỗi:', error));
    }
    function showReceiptDetails(receiptId) {
        fetch(`includes/right_content/suppliers/receipts.php?action=receipt_details&receipt_id=${receiptId}`)
            .then(response => response.text())
            .then(responseData => {
                try {
                    let data = JSON.parse(responseData);
                    if (data.success) {
                        const receipt = data.receipt;
                        const details = data.details;
                        const groupedDetails = details.reduce((acc, detail) => {
                            const productId = detail.product_id;
                            if (!acc[productId]) {
                                acc[productId] = {
                                    product_id: productId,
                                    product_name: detail.product_name,
                                    price: detail.price,
                                    sizes: []
                                };
                            }
                            acc[productId].sizes.push({
                                size: detail.size,
                                quantity: detail.quantity
                            });
                            return acc;
                        }, {});
                        const groupedDetailsList = Object.values(groupedDetails);
                        receiptInfoDiv.innerHTML = `
                            <div><strong>Trạng thái:</strong> Đã hoàn thành</div>
                            <div><strong>Nhà cung cấp:</strong> ${receipt.supplier_name}</div>
                            <div><strong>Nhân viên:</strong> ${receipt.employee}</div>
                            <div><strong>Ngày tạo phiếu:</strong> ${new Date(receipt.receipt_date).toLocaleDateString('vi-VN')}</div>
                            <div><strong>Chiết khấu:</strong> ${receipt.discount_percent}%</div>
                        `;
                        receiptProductRows.innerHTML = '';
                        groupedDetailsList.forEach((product, index) => {
                            const discountPercent = parseFloat(receipt.discount_percent) || 0;
                            const sellPrice = product.price * (1 + discountPercent / 100);
                            const totalPrice = product.sizes.reduce((sum, size) => sum + (size.quantity * product.price), 0);

                            const row = document.createElement('tr');
                            const sizesHtml = product.sizes.map((size, sizeIndex) => `
                                <div class="size-input-group">
                                    <input type="text" name="size[${index}][${sizeIndex}]" value="${size.size}" disabled>
                                    <input type="number" name="quantity[${index}][${sizeIndex}]" value="${size.quantity}" disabled>
                                    <button type="button" class="remove-size-btn" style="display: none;">❌</button>
                                </div>
                            `).join('');

                            row.innerHTML = `
                                <td class="stt">${index + 1}</td>
                                <td>
                                    <input type="text" class="product-search" value="${product.product_name}" data-product-id="${product.product_id}" disabled>
                                    <ul class="suggestions-product-list" style="display: none;"></ul>
                                </td>
                                <td>
                                    <div class="sizes-wrapper">
                                        ${sizesHtml}
                                    </div>
                                    <button type="button" class="add-size-btn" style="display: none;">+ Thêm size</button>
                                </td>
                                <td><input type="number" name="price[${index}]" class="price" value="${product.price}" disabled></td>
                                <td><span class="sell-price">${sellPrice.toLocaleString('vi-VN')} đ</span></td>
                                <td><span class="total-price">${totalPrice.toLocaleString('vi-VN')} đ</span></td>
                                <td><button type="button" class="remove-row-btn" style="display: none;">🗑️</button></td>
                            `;
                            receiptProductRows.appendChild(row);
                        });
                        totalAmountSpan.textContent = `${parseFloat(receipt.total_amount).toLocaleString('vi-VN')} đ`;
                        addRowBtn.style.display = 'none';
                        submitBtn.style.display = 'none';
                        detailFormContainer.style.display = 'flex';
                    } else {
                        alert("Lỗi: " + data.error);
                    }
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert("Có lỗi xảy ra khi lấy chi tiết phiếu nhập!");
            });
    }
    if (closeDetailFormBtn) {
        closeDetailFormBtn.addEventListener('click', () => {
            detailFormContainer.style.display = 'none';
            addRowBtn.style.display = 'inline-block';
            submitBtn.style.display = 'inline-block';
            document.getElementById('receipt-add-form').style.display = 'none';
        });
    }

    loadReceipts();

}

function validateEmail(input) {
    const regex = /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    const emailErrora = document.getElementById("email-error");
    const emailErrorm = document.getElementById("emailm-error");
    if (!input.value) {
        if (emailErrora)
            emailErrora.style.display = "none";
        if (emailErrorm)
            emailErrorm.style.display = "none";
        return false;
    }
    if (!regex.test(input.value)) {
        if (emailErrora)
            emailErrora.style.display = "inline";
        if (emailErrorm)
            emailErrorm.style.display = "inline";
        return false;
    } else {
        if (emailErrora)
            emailErrora.style.display = "none";
        if (emailErrorm)
            emailErrorm.style.display = "none";
        return true;
    }
}

function validatePhone(input) {
    const regex = /^(0|\+84)[0-9]{9,10}$/;
    const phoneErrora = document.getElementById("phone-error");
    const phoneErrorm = document.getElementById("phonem-error");
    if (!input.value) {
        if (phoneErrora)
            phoneErrora.style.display = "none";
        if (phoneErrorm)
            phoneErrorm.style.display = "none";
        return false;
    }
    if (!regex.test(input.value)) {
        if (phoneErrora)
            phoneErrora.style.display = "inline";
        if (phoneErrorm)
            phoneErrorm.style.display = "inline";
        return false;
    } else {
        if (phoneErrora)
            phoneErrora.style.display = "none";
        if (phoneErrorm)
            phoneErrorm.style.display = "none";
        return true;
    }
}