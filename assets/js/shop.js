//javascript in shop.html
function toggleSize() {
    const header = document.querySelector('.filter-header');
    const options = document.getElementById('gender-options');
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
    header.classList.toggle('collapsed');
  }

  function toggleSize1() {
    const header = document.querySelector('.filter-header-color');
    const options = document.getElementById('color-options');
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
    header.classList.toggle('collapsed');
  }

  function toggleSize2() {
    const header = document.querySelector('.filter-header-collection');
    const options = document.getElementById('collection-options');
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
    header.classList.toggle('collapsed');
  }



  

  const products = [
    {
        name: "Nike Pegasus Trail 5T-Shirt 14A",
        tag: "Hàng mới",
        image: "/web2/assets/images/14.png",
        price: 1000,
        title: "Giày cho mấy nhóc già đầu đi",
        color: "1 Mẫu màu"    
    },
    {
        name: "Nike Pegasus Trail 5",
        tag: "Hàng mới",
        image: "/web2/assets/images/14.png",
        price: 1000,
        title: "Giày cho mấy nhóc già đầu đi",
        color: "1 Mẫu màu"    
    },
    {
        name: "Nike Pegasus Trail 5",
        tag: "Hàng mới",
        image: "/web2/assets/images/14.png",
        price: 1000,
        title: "Giày cho mấy nhóc già đầu đi",
        color: "1 Mẫu màu"    
    },
    {
        name: "Nike Pegasus Trail 5",
        tag: "Hàng mới",
        image: "/web2/assets/images/14.png",
        price: 1000,
        title: "Giày cho mấy nhóc già đầu đi",
        color: "1 Mẫu màu"    
    },
    {
        name: "Nike Pegasus Trail 5",
        tag: "Hàng mới",
        image: "/web2/assets/images/14.png",
        price: 1000,
        title: "Giày cho mấy nhóc già đầu đi",
        color: "1 Mẫu màu"    
    },
    {
        name: "Nike Pegasus Trail 5",
        tag: "Hàng mới",
        image: "/web2/assets/images/14.png",
        price: 1000,
        title: "Giày cho mấy nhóc già đầu đi",
        color: "1 Mẫu màu"    
    }
];

const productsPerPage = 4; // Số sản phẩm trên mỗi trang
let currentPage = 1;

function displayProducts(page) {
    const productContainer = document.getElementById('product-for-shop');
    productContainer.innerHTML = ""; 

    const startIndex = (page - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    const paginatedProducts = products.slice(startIndex, endIndex);

    paginatedProducts.forEach(product => {
        const productHTML = `
            <div class="product">
                <a href="product.html">
                    <img src="${product.image}" alt="Shoe">
                    <p class="tag">${product.tag}</p>
                    <h3>${product.name}</h3>
                    <p>${product.title}</p>
                    <p>${product.color}</p>
                    <p class="price">${product.price}$</p>
                </a>
            </div>
        `;
        productContainer.innerHTML += productHTML;
    });

    updatePaginationButtons();
}

function updatePaginationButtons() {
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === Math.ceil(products.length / productsPerPage);
}

function changePage(step) {
    currentPage += step;
    displayProducts(currentPage);
}

// Thêm nút phân trang vào HTML
document.addEventListener("DOMContentLoaded", function () { // lắng nghe sự kiện khi được load
    const paginationControls = `
        <div class="pagination">
            <button id="prevBtn" onclick="changePage(-1)">Previous</button>
            <span> Page <span id="currentPage">1</span> </span>
            <button id="nextBtn" onclick="changePage(1)">Next</button>
        </div>
    `;
    document.querySelector('.product-list').insertAdjacentHTML('beforeend', paginationControls);
    displayProducts(currentPage);
});
