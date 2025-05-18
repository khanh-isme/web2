

 function handleLogin()
{
    let password = document.getElementById("login-password");


    let loginForm=document.querySelector("#login-form");

    loginForm.addEventListener("submit", (ev)=>{
        ev.preventDefault();

        const formData = new FormData(loginForm);

        fetch('includes/check_account.php' ,{
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(responseData => {
            try
            {
                console.log(responseData); 
                data=JSON.parse(responseData);
                showMessageDialog(data.message);
                if(data.status === "success")
                {
                    
                    document.querySelector('.nav-link').click();
                    showGreeting(data.username); // << Gọi hàm thay thế giao diện
                    
                    window.user_id = data.user_id; // ← Gán user_id thành biến toàn cục
                    initCart(); 
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

async function initCart() {
    
    if (!window.user_id) {
        console.warn("user_id chưa được khởi tạo");
        return;
    }

    window.cartUser = await getCartItem(window.user_id);
    console.log(window.cartUser);
}


// Chuyển form
function gannutlogin(){
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });
    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
}

//ử lý AJAX cho đăng ký
function handleSigin() {
    const form = document.querySelector('#sign-up');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('/web2/includes/sign.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(responseText => {
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (error) {
                console.error('Lỗi khi parse JSON:', error);
                console.error('Dữ liệu nhận được:', responseText);
                return;
            }

            // Hiển thị thông báo
            showMessageDialog(data.message);

            if (data.status === "success") {
                document.getElementById('register').classList.remove("active");
                form.reset(); // Reset form nếu cần
            }
        })
        .catch(error => {
            console.error('Lỗi fetch:', error);
        });
    });
}



