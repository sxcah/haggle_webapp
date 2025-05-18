const loginGeneralError = document.getElementById('general-error');
const loginUsername = document.getElementById('username');
const loginUsernameError = document.getElementById('username-error');
const loginPassword = document.getElementById('password');
const loginPasswordError = document.getElementById('password-error');
const loginShowPassword = document.getElementById('show-password');
const loginForm = document.getElementById('login-form');
const loginSubmitButton = document.getElementById('submit-button');

loginForm.addEventListener('submit', async function(event) {
    event.preventDefault();

    // Reset error messages
    loginUsernameError.textContent = '';
    loginPasswordError.textContent = '';
    loginGeneralError.textContent = '';

    const formData = new FormData(loginForm);

    try {
        const response = await fetch('../database/login-user.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            window.location.href = '../consumer/dashboard.html';
        } else {
            loginGeneralError.textContent = data.error || 'Login failed.';
            loginGeneralError.style.display = 'block';
        }
    } catch (error) {
        loginGeneralError.textContent = 'An unexpected error occurred.';
        loginGeneralError.style.display = 'block';
    }
});

function togglePasswordVisibility() {
    if (loginPassword.type === 'password') {
        loginPassword.type = 'text';
        loginShowPassword.value = 'Hide Password';
    } else {
        loginPassword.type = 'password';
        loginShowPassword.value = 'Show Password';
    }
}