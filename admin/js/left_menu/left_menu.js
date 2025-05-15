function activeMenuItem() {
    let menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(el => {
        el.addEventListener("click", () => {
            if (!el.classList.contains('active')) {
                hideEl = document.querySelector('.menu-item.active')
                if (hideEl != null) {
                    hideEl.classList.remove('active');
                    let hideContent = document.getElementsByClassName(hideEl.dataset.value)[1];
                    hideContent.classList.remove('active');
                }

                el.classList.add('active');
                let content = document.getElementsByClassName(el.dataset.value)[1];
                if (content != null) {
                    content.classList.add('active');
                }
            }
        });
    });

    document.getElementById("account").addEventListener("click", (e) => {
        e.stopPropagation();
        let changPasswordBtn = document.getElementById("change-password-btn");
        if (changPasswordBtn.style.display === "flex") {
            changPasswordBtn.style.display = "none";
        }
        else {
            changPasswordBtn.style.display = "flex";
        }
    });

    document.addEventListener("click", function (event) {
        let changPasswordBtn = document.getElementById("change-password-btn");

        if (changPasswordBtn.style.display === "flex") {
            if (!changPasswordBtn.contains(event.target)) {
                changPasswordBtn.style.display = "none";
            }
        }
    });

    document.getElementById("change-password-btn").addEventListener("click", () => {
        document.getElementById("right_content").style.pointerEvents = 'none';
        document.getElementById("change-password-form").classList.add("active");
    });

    document.getElementById("change-password-form-x").addEventListener("click", () => {
        let passwordForm = document.getElementById("change-password-form");
        document.getElementById("right_content").style.pointerEvents = 'auto';
        passwordForm.classList.remove("active");
    });

    document.querySelector("#change-password-form").addEventListener("submit", (ev) => {
        ev.preventDefault();

        let form = document.getElementById('change-password-form');
        let inputs = form.getElementsByTagName('input');

        let isValid = true;

        for (let input of inputs) {
            if (input.type !== 'submit' && input.value.trim() === '') {
                isValid = false;
                input.style.borderColor = 'red';
            } else {
                input.style.borderColor = 'gray';
            }
        }

        if (!isValid) {
            showMessageDialog('<p><i class="fa-regular fa-circle-xmark red icon"></i>Vui lòng điền tất cả các trường!</p>');
        } else {
            if (inputs[1].value != inputs[2].value) {
                showMessageDialog('<p><i class="fa-regular fa-circle-xmark red icon"></i>Xác nhận mật khẩu không khớp!</p>');
                inputs[2].style.borderColor = 'red';
                inputs[2].focus();
            }
            else {
                inputs[2].style.borderColor = 'gray';
                fetch("includes/login_signin/admin_change_password.php", {
                    method: 'POST',
                    body: new FormData(document.getElementById("change-password-form"))
                })
                    .then(response => response.text())
                    .then(responseData => {
                        try {
                            let data = JSON.parse(responseData);
                            showMessageDialog(data.message);
                            if (data.status === "password_incorrect") {
                                inputs[0].style.borderColor = 'red';
                                inputs[0].focus();
                            }
                            if (data.status === "success") {
                                document.getElementById("change-password-form").classList.remove("active");
                            }
                        }
                        catch (error) {
                            console.error(error);
                            console.error(responseData);
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }
    });
}
