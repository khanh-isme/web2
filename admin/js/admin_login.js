function checkInput()
{
    let username = document.getElementById("admin-login-username");
    let password = document.getElementById("admin-login-password");
    let loginBtn = document.getElementById("admin-login-button");
    if (username.value&&password.value)
    {
        loginBtn.disabled=false;
    }
    else
    {
        loginBtn.disabled=true;
    }
}

function handleLogin()
{
    let username = document.getElementById("admin-login-username");
    let password = document.getElementById("admin-login-password");

    username.addEventListener("input",checkInput);
    password.addEventListener("input",checkInput);

    let loginForm=document.querySelector("#admin-login-form");

    loginForm.addEventListener("submit", (ev)=>{
        ev.preventDefault();

        const formData = new FormData(loginForm);

        fetch('includes/admin_check_account.php' ,{
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(responseData => {
            try
            {
                data=JSON.parse(responseData);
                showMessageDialog(data.message);
                if(data.status === "success")
                {
                    document.querySelector('#body').innerHTML=data.html;
                    itemDefault=document.getElementsByClassName(data.default);
                    Array.from(itemDefault).forEach(el => {
                        el.classList.add('active');
                    });
                    activeMenuItem();
                    handleLogout();
                    orderfunc();
                    customerfunc();
                    handleStatsMenu();
                    handleChart();
                }
                else
                {
                    password.select();
                }
            }
            catch(error)
            {   
                console.error('Có lỗi xảy ra: ',error);
                console.error(responseData);
            }
        })
        .catch(error => {
            console.error('Có lỗi xảy ra: ', error);
            alert('Đã xảy ra lỗi khi đăng nhập. Vui lòng thử lại.');
        });
    });
}