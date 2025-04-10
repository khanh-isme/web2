function logout()
{
    fetch("includes/admin_logout.php")
    .then(response => response.text())
    .then(responseData => {
        try
        {
            data=JSON.parse(responseData);
            showMessageDialog(data.message);
            document.querySelector('#body').innerHTML=data.html;
            handleLogin();
        }
        catch(error)
        {
            console.error('Có lỗi xảy ra: ',error);
            console.error(responseData);
        }
    });
}

function handleLogout()
{
    let logoutBtn=document.querySelector('#admin-logout-button');
    logoutBtn.addEventListener('click',logout);
}


