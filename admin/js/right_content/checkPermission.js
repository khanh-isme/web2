async function checkPermission(permission) {
    return fetch('includes/login_signin/checkPermission.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ permission: permission })
    })
        .then(response => response.text())
        .then(responseData => {
            try {
                let data = JSON.parse(responseData);
                return data.allowed === true;
            }
            catch (error) {
                console.error(error);
                console.error(responseData);
            }
        })
        .catch(error => {
            console.error('Error checking permission:', error);
            return false;
        });
}
