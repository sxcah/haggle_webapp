const accountGeneralError = document.getElementById('general-error');
const accountUsername = document.getElementById('username');
const accountUsernameError = document.getElementById('username-error');
const accountPassword = document.getElementById('password');
const accountPasswordError = document.getElementById('password-error');
const accountConfirmPassword = document.getElementById('confirm-password');
const accountConfirmPasswordError = document.getElementById('confirm-password-error');
const accountShowPassword = document.getElementById('show-password');
const accountSubmitButton = document.querySelector('input[type="submit"]');
const accountForm = document.getElementById('register-account-form');

const usernameRegex = /^[a-zA-Z0-9_]+$/;
const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

const usernameErrorMessage = "Username must be alphanumeric and can include underscores.";
const passwordErrorMessage = "Password must be at least 8 characters long and contain at least one letter and one number.";
const confirmPasswordErrorMessage = "Passwords do not match.";

let isUsernameValid = false;
let isPasswordValid = false;
let isConfirmPasswordValid = false;

function togglePasswordVisibility() {
    if (accountPassword.type === 'password') {
        accountPassword.type = 'text';
        accountConfirmPassword.type = 'text';
        accountShowPassword.value = 'Hide Password';
    } else {
        accountPassword.type = 'password';
        accountConfirmPassword.type = 'password';
        accountShowPassword.value = 'Show Password';
    }
}

function validateUsername() {
    const username = accountUsername.value.trim();

    if (!usernameRegex.test(username)) {
        accountUsernameError.textContent = usernameErrorMessage;
        accountUsernameError.style.display = "block";
        isUsernameValid = false;
    } else {
        accountUsernameError.textContent = "";
        accountUsernameError.style.display = "none";
        isUsernameValid = true;
    }

    enableDisableSubmit();
}

function validatePassword() {
    const password = accountPassword.value.trim();

    if (!passwordRegex.test(password)) {
        accountPasswordError.textContent = passwordErrorMessage;
        accountPasswordError.style.display = "block";
        isPasswordValid = false;
    } else {
        accountPasswordError.textContent = "";
        accountPasswordError.style.display = "none";
        isPasswordValid = true;
    }

    enableDisableSubmit();
}

function validateConfirmPassword() {
    const password = accountPassword.value.trim();
    const confirmPassword = accountConfirmPassword.value.trim();

    if (password !== confirmPassword) {
        accountConfirmPasswordError.textContent = confirmPasswordErrorMessage;
        accountConfirmPasswordError.style.display = "block";
        isConfirmPasswordValid = false;
    } else {
        accountConfirmPasswordError.textContent = "";
        accountConfirmPasswordError.style.display = "none";
        isConfirmPasswordValid = true;
    }

    enableDisableSubmit();
}

async function registerUser(formData) {
    try {
        const response = await fetch('../database/register-account.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData).toString(),
        });

        if (response.ok) {
            window.location.href = '../forms/login.html';
        } else {
            const errorData = await response.json();
            displayServerSideErrors(errorData);
        }
    } catch (error) {
        console.error('Error during registration:', error);
        accountGeneralError.textContent = 'An unexpected error occurred. Please try again later.';
    }
}

accountForm.addEventListener('submit', function(event) {
    event.preventDefault();
    if (validateForm()) {
        const formData = new FormData(accountForm);
        registerUser(formData);
    } else {
        accountGeneralError.textContent = "Please fix the errors above.";
        accountGeneralError.style.display = "block";
    }
});

function enableDisableSubmit() {
    accountSubmitButton.disabled = !(isUsernameValid && isPasswordValid && isConfirmPasswordValid);
}

function validateForm() {
    validateUsername();
    validatePassword();
    validateConfirmPassword();

    if (isUsernameValid && isPasswordValid && isConfirmPasswordValid) {
        accountGeneralError.style.display = "none";
        return true;
    } else {
        accountGeneralError.textContent = "Please fix the errors above.";
        accountGeneralError.style.display = "block";
        return false;
    }
}

enableDisableSubmit();

accountUsername.addEventListener('input', validateUsername);
accountPassword.addEventListener('input', validatePassword);
accountConfirmPassword.addEventListener('input', validateConfirmPassword);