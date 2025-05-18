 // nơi viết các hàm golobal chạy trong web


let messageDialog=document.querySelector('#messageDialog');
function showMessageDialog(message)
{
    
    if(message!=='')
    {
        messageDialog.innerHTML=message;
        messageDialog.style.animation='none';
        messageDialog.offsetHeight;
        messageDialog.style.animation='showMessageDialog 5s ease forwards';
    }
}

messageDialog.addEventListener("click", ()=>{
    messageDialog.style.animation='none';
    messageDialog.style.transform="translateX(100%)";
    messageDialog.style.opacity="0";
});



async function getProductSizeInfo(product_size_id) {
    const res = await fetch(`includes/get_product_size_info.php?id=${product_size_id}`);
    const data = await res.json();
    return data.status === 'success' ? data.product_size : null;
}


async function getProductInfo(product_id) {
    const res = await fetch(`includes/get_product_info.php?id=${product_id}`);
    const data = await res.json();
    return data.status === 'success' ? data.product: null;
}

async function getCartItem(user_id) {
    const res = await fetch(`includes/get_cart.php?user_id=${user_id}`);
    const data = await res.json();
    return data.status === 'success' ? data.cart_items : [];
}
