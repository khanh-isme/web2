
fetch('includes/admin_check_login.php')
.then(response => response.text())
.then(responseData => {
    try
    {
        data=JSON.parse(responseData);
        showMessageDialog(data.message);
        if(data.status==="success")
        {
            document.querySelector('#body').innerHTML=data.html;
            itemDefault=document.getElementsByClassName(data.default);
            Array.from(itemDefault).forEach(el => {
                el.classList.add('active');
            });
            activeMenuItem();
            handleLogout();
        }
        else
        {
            document.querySelector('#body').innerHTML=data.html;
            handleLogin();
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

