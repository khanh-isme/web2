
fetch('includes/check_login.php',{method:'POST'})
.then(response => response.text())
.then(responseData => {
    try
    {   
        data=JSON.parse(responseData);
        showMessageDialog(data.message);
        if(data.status==="success")
        {
            
            showGreeting(data.username);
            window.user_id = data.user_id; // ← Gán user_id thành biến toàn cục
            console.log(window.user_id);
            initCart();     
        }
        
    }
    catch(error)
    {
        console.error(error);
        console.error(responseData);
    }
})
.catch(error => {
    console.error('Có lỗi xảy ra:', error);
    alert('Đã xảy ra lỗi khi đăng nhập. Vui lòng thử lại.');
});
