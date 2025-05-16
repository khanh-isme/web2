function closeAndOpenProductForm() {
    loadProducts();

    const editModal = document.getElementById("product-edit-modal");
    const editModalClose = editModal?.querySelector(".product-close-button");
    const addModal = document.getElementById("product-add-modal");
    const addProductButton = document.querySelector(".product-add-button");
    const addModalClose = addModal?.querySelector(".product-add-close-button");
    const table = document.querySelector(".product-table");
    const changeImageInput = document.getElementById("product-change-image");
    const editForm = document.getElementById("product-edit-form");
    const addProductImageInput = document.getElementById('add-product-image');
    const addProductImagePreview = document.getElementById('add-product-image-preview');
    const addProductForm = document.getElementById('product-add-form');
    fetch("includes/right_content/products/getCategories.php")
        .then(response => response.text())
        .then(responseData => {
            try {
                let data = JSON.parse(responseData);
                const categoryFilterSelect = document.getElementById("product-filter-category");

                categoryFilterSelect.innerHTML = '';
                categoryFilterSelect.appendChild(new Option('-- Select Category --', '', true, true));
                data.categories.forEach(category => {
                    const option = document.createElement("option");
                    option.value = category.id;
                    option.textContent = category.name;
                    categoryFilterSelect.appendChild(option);
                });

            }
            catch (error) {
                console.error(error);
                console.error(responseData);
            }
        })
        .catch(error => {
            console.error("Error fetching categories:", error);
        });
    productFilter();
    table.addEventListener("click", (event) => {
        const target = event.target.closest('button');
        if (!target) return;

        const productId = target.getAttribute("data-id");
        if (target.classList.contains("product-edit-button")) {
            fetch(`includes/right_content/products/getProductByID.php?id=${productId}`)
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let product = JSON.parse(responseData);
                        openEditProductModal(product);
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    console.error("Error fetching product data:", error);
                });
        }
        if (target.classList.contains("product-delete-button")) {
            if (confirm(`Bạn có chắc chắn muốn xóa sản phẩm với ID ${productId}?`)) {
                fetch('includes/right_content/products/actionProduct.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete', id: productId })
                })
                    .then(response => response.text())
                    .then(responseData => {
                        try {
                            let data = JSON.parse(responseData);
                            if (data.success) {
                                alert(data.message);
                                loadProducts();
                            } else {
                                alert('Lỗi: ' + data.message);
                            }
                        }
                        catch (error) {
                            console.error(error);
                            console.error(responseData);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting product:', error);
                        alert('Có lỗi xảy ra khi xóa sản phẩm!');
                    });
            }
        }
    });
    if (editModalClose) {
        editModalClose.addEventListener("click", () => {
            if (editModal) {
                editModal.style.display = "none";
            }
        });
    }
    if (addProductButton) {
        addProductButton.addEventListener("click", () => {
            if (addModal) {
                openAddProductModal();
            }
        });
    }
    if (addModalClose) {
        addModalClose.addEventListener("click", () => {
            if (addModal) {
                addModal.style.display = "none";
            }
        });
    }
    window.addEventListener("click", (event) => {
        if (event.target === editModal) {
            editModal.style.display = "none";
        }
        if (event.target === addModal) {
            addModal.style.display = "none";
        }
    });

    if (editForm) {
        changeImageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            const editImage = document.getElementById("product-edit-image");

            if (file) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    editImage.src = e.target.result;
                };

                reader.readAsDataURL(file);
            } else {
                console.log('No file selected');
                editImage.src = './imgs/blank.png';
            }
        });

        editForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'update');
            const imagePreview = document.getElementById('product-edit-image');
            if (imagePreview && imagePreview.src) {
                formData.append('image_src', imagePreview.src);
            }

            fetch('includes/right_content/products/actionProduct.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        if (data.success) {
                            alert(data.message);
                            editModal.style.display = "none";
                            loadProducts();
                        } else {
                            alert('Failed to update product: ' + data.message);
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the product.');
                });
        });
    }

    if (addProductForm) {
        addProductImageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    addProductImagePreview.src = e.target.result;
                    addProductImagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
        addProductForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'add');

            fetch('includes/right_content/products/actionProduct.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        if (data.success) {
                            showMessageDialog(data.message);
                            addModal.style.display = "none";
                            addProductForm.reset();
                            addProductImagePreview.style.display = 'none';
                            loadProducts();
                        } else {
                            alert('Failed to add product: ' + data.message);
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the product.');
                });
        });
    }
}

function loadProducts() {
    fetch('includes/right_content/products/getProducts.php')
        .then(response => response.text())
        .then(responseData => {
            let products = JSON.parse(responseData);
            const tbody = document.querySelector('.product-table tbody');
            tbody.innerHTML = '';
            Promise.all([
                checkPermission('EDIT_PRODUCT'),
                checkPermission('DELETE_PRODUCT')
            ]).then(([canEdit, canDelete]) => {
                if (products.length > 0) {
                    products.forEach(product => {
                        const imagePath = product.image ? `${product.image}` : './imgs/blank.png';

                        let actionButtons = '';
                        if (canEdit) {
                            actionButtons += `<button class="product-edit-button" data-id="${product.id}"><i class="fa-solid fa-circle-info"></i> Edit</button>`;
                        }
                        if (canDelete) {
                            actionButtons += `<button class="product-delete-button" data-id="${product.id}"><i class="fa-solid fa-trash"></i> Delete</button>`;
                        }

                        const row = `
                            <tr>
                                <td><img src="${imagePath}" alt="Product Image" class="product-image" width="50"></td>
                                <td>${product.name}</td>
                                <td>${product.category_name}</td>
                                <td>${product.total_stock || 0}</td>
                                <td>${product.price == 0 ? 'Chưa nhập giá' : `${product.price}đ`}</td>
                                <td>${actionButtons}</td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="6">Không tìm thấy sản phẩm</td></tr>';
                }
            });
        })
        .catch(error => {
            console.error('Error loading products:', error);
        });
}

function openEditProductModal(product) {
    const editModal = document.getElementById("product-edit-modal");

    const editImage = document.getElementById("product-edit-image");
    const editId = document.getElementById("product-edit-id");
    const editName = document.getElementById("product-edit-name");
    const editPrice = document.getElementById("product-edit-price");
    const editCategory = document.getElementById("product-edit-category");
    const editGender = document.getElementById("product-edit-gender");
    const editDetails = document.getElementById("product-edit-details");
    const editStock = document.getElementById("product-edit-stock");
    editImage.src = product.image ? `${product.image}` : './imgs/blank.png';
    editId.value = product.id;
    editName.value = product.name;
    editPrice.value = product.price;
    editGender.value = product.gender;
    editDetails.value = product.description;

    if (product.sizes && product.sizes.length > 0) {
        const sizeStockDetails = product.sizes
            .map(size => `size ${size.size}: ${size.stock}`)
            .join(`\n`);
        editStock.value = sizeStockDetails;
    } else {
        editStock.value = 'Chưa có thông tin size';
    }
    editStock.disabled = true;
    fetch('includes/right_content/products/getCategories.php')
        .then(response => response.text())
        .then(responseData => {
            try {
                let data = JSON.parse(responseData);
                editCategory.innerHTML = '';
                data.categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    if (category.id == product.category_id) option.selected = true;
                    editCategory.appendChild(option);
                });
            }
            catch (error) {
                console.error(error);
                console.error(responseData);
            }
        })
        .catch(error => {
            console.error('Error loading categories:', error);
        });



    editModal.style.display = "flex";
}

function openAddProductModal() {
    const addModal = document.getElementById("product-add-modal");

    const addProductForm = document.getElementById('product-add-form');
    fetch('includes/right_content/products/getCategories.php')
        .then(response => response.text())
        .then(responseData => {
            try {
                let data = JSON.parse(responseData);
                const categorySelect = document.getElementById('add-product-category');
                categorySelect.innerHTML = '';

                if (data.categories && data.categories.length > 0) {
                    data.categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        categorySelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No categories available';
                    categorySelect.appendChild(option);
                }
            }
            catch (error) {
                console.error(error);
                console.error(responseData);
            }
        })
        .catch(error => {
            console.error('Error loading categories:', error);
            alert('Không thể tải danh sách danh mục. Vui lòng thử lại sau.');
        });
    addProductForm.reset();
    addModal.style.display = "flex";
}

function checkProductId() {
    const productId = document.getElementById('add-product-id').value;

    if (!productId) return;

    fetch(`includes/right_content/products/getProductByID.php?id=${productId}`)
        .then(response => response.text())
        .then(responseData => {
            try {
                let product = JSON.parse(responseData);
                if (product && !product.error) {
                    alert('Product ID already exists. You can only update stock and supplier information.');

                    document.getElementById('add-product-name').value = product.name || '';
                    document.getElementById('add-product-name').readOnly = true;

                    document.getElementById('add-product-category').value = product.category_id || '';
                    document.getElementById('add-product-category').disabled = true;

                    document.getElementById('add-product-gender').value = product.gender || '';
                    document.getElementById('add-product-gender').readOnly = true;

                    document.getElementById('add-product-description').value = product.description || '';
                    document.getElementById('add-product-description').readOnly = true;
                } else {
                    document.getElementById('add-product-name').value = '';
                    document.getElementById('add-product-name').readOnly = false;

                    document.getElementById('add-product-category').value = '';
                    document.getElementById('add-product-category').disabled = false;

                    document.getElementById('add-product-gender').value = '';
                    document.getElementById('add-product-gender').readOnly = false;

                    document.getElementById('add-product-description').value = '';
                    document.getElementById('add-product-description').readOnly = false;
                }
            }
            catch (error) {
                console.error(error);
                console.error(responseData);
            }
        })
        .catch(error => {
            console.error('Error checking product ID:', error);
        });
}

function productFilter() {
    const filterForm = document.getElementById('product-filter-form');

    filterForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('includes/right_content/products/productFilter.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(responseData => {
                try {
                    let products = JSON.parse(responseData);
                    const tbody = document.querySelector('.product-table tbody');
                    tbody.innerHTML = '';

                    Promise.all([
                        checkPermission('EDIT_PRODUCT'),
                        checkPermission('DELETE_PRODUCT')
                    ]).then(([canEdit, canDelete]) => {
                        if (products.length > 0) {
                            products.forEach(product => {
                                const imagePath = product.image ? `${product.image}` : './imgs/blank.png';

                                let actionButtons = '';
                                if (canEdit) {
                                    actionButtons += `<button class="product-edit-button" data-id="${product.id}"><i class="fa-solid fa-circle-info"></i> Edit</button>`;
                                }
                                if (canDelete) {
                                    actionButtons += `<button class="product-delete-button" data-id="${product.id}"><i class="fa-solid fa-trash"></i> Delete</button>`;
                                }

                                const row = `
                                    <tr>
                                        <td><img src="${imagePath}" alt="Product Image" class="product-image" width="50"></td>
                                        <td>${product.name}</td>
                                        <td>${product.category_name}</td>
                                        <td>${product.total_stock || 0}</td>
                                        <td>${product.price == 0 ? 'Chưa nhập giá' : `${product.price}đ`}</td>
                                        <td>${actionButtons}</td>
                                    </tr>
                                `;
                                tbody.insertAdjacentHTML('beforeend', row);
                            });
                        } else {
                            tbody.innerHTML = '<tr><td colspan="6">Không tìm thấy sản phẩm</td></tr>';
                        }
                    });
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(error => {
                console.error('Error filtering products:', error);
            });
    });
}
