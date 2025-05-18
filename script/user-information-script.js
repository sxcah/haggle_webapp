const registerInfoGeneralError = document.getElementById('general-error');
const registerInfoFirstname = document.getElementById('firstname');
const registerInfoFirstnameError = document.getElementById('firstname-error');
const registerInfoLastname = document.getElementById('lastname');
const registerInfoLastnameError = document.getElementById('lastname-error');
const registerInfoEmail = document.getElementById('email');
const registerInfoEmailError = document.getElementById('email-error');
const registerInfoAddress = document.getElementById('address');
const registerInfoAddressError = document.getElementById('address-error');
const registerInfoContactNumber = document.getElementById('contact-number');
const registerInfoContactNumberError = document.getElementById('contact-number-error');
const registerInfoSubmitButton = document.getElementById('submit-button');
const registerForm = document.getElementById('register-form');

const nameRegex = /^[a-zA-Z]+$/;
const contactNumberRegex = /^\d{11}$/;
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

let isNameValid = false;
let isEmailValid = false;
let isContactNumberValid = false;
let isAddressValid = false;

function validateName() {
    const firstname = registerInfoFirstname.value.trim();
    const lastname = registerInfoLastname.value.trim();

    let isFirstNameValid = false;
    let isLastNameValid = false;

    if (!nameRegex.test(firstname)) {
        registerInfoFirstnameError.textContent = "Invalid First Name!";
        registerInfoFirstnameError.style.display = "block";
    } else {
        registerInfoFirstnameError.textContent = "";
        registerInfoFirstnameError.style.display = "none";
        isFirstNameValid = true;
    }

    if (!nameRegex.test(lastname)) {
        registerInfoLastnameError.textContent = "Invalid Last Name!";
        registerInfoLastnameError.style.display = "block";
    } else {
        registerInfoLastnameError.textContent = "";
        registerInfoLastnameError.style.display = "none";
        isLastNameValid = true;
    }

    isNameValid = isFirstNameValid && isLastNameValid;

    enableDisableSubmit();
}

function validateEmail() {
    const email = registerInfoEmail.value.trim();

    if (!emailRegex.test(email)) {
        registerInfoEmailError.textContent = "Invalid Email!";
        registerInfoEmailError.style.display = "block";
        isEmailValid = false;
    } else {
        registerInfoEmailError.textContent = "";
        registerInfoEmailError.style.display = "none";
        isEmailValid = true;
    }

    enableDisableSubmit();
}

function validateContactNumber() {
    const contactNumber = registerInfoContactNumber.value.trim();

    if (!contactNumberRegex.test(contactNumber)) {
        registerInfoContactNumberError.textContent = "Invalid Contact Number!";
        registerInfoContactNumberError.style.display = "block";
        isContactNumberValid = false;
    } else {
        registerInfoContactNumberError.textContent = "";
        registerInfoContactNumberError.style.display = "none";
        isContactNumberValid = true;
    }

    enableDisableSubmit();
}

function validateAddress() {
    const address = registerInfoAddress.value.trim();

    if (address === '') {
        registerInfoAddressError.textContent = "Address Required!";
        registerInfoAddressError.style.display = "block";
        isAddressValid = false;
    } else {
        registerInfoAddressError.textContent = "";
        registerInfoAddressError.style.display = "none";
        isAddressValid = true;
    }

    enableDisableSubmit();
}

function enableDisableSubmit() {
    registerInfoSubmitButton.disabled = !(isNameValid && isEmailValid && isContactNumberValid && isAddressValid);
}

function validateForm() {
    validateName();
    validateEmail();
    validateContactNumber();
    validateAddress();

    if (isNameValid && isEmailValid && isContactNumberValid && isAddressValid) {
        registerInfoGeneralError.style.display = "none";
        return true;
    } else {
        registerInfoGeneralError.textContent = "Please fix the errors above.";
        registerInfoGeneralError.style.display = "block";
        return false;
    }
}

function displayServerSideErrors(result) {
    if (result && result.error) {
        registerInfoGeneralError.textContent = result.error;
        registerInfoGeneralError.style.display = "block";
    } else {
        registerInfoGeneralError.textContent = "An unknown error occurred.";
        registerInfoGeneralError.style.display = "block";
    }
}

registerForm.addEventListener('submit', function(event) {
    event.preventDefault();
    if (validateForm()) {
        const formData = new FormData(registerForm);
        // Convert FormData to URLSearchParams for fetch
        fetch('../database/register-information.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData).toString(),
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                window.location.href = result.redirect;
            } else {
                displayServerSideErrors(result);
            }
        })
        .catch(error => {
            console.error('Error during registration:', error);
            registerInfoGeneralError.textContent = 'An unexpected error occurred. Please try again later.';
            registerInfoGeneralError.style.display = "block";
        });
    }
});

enableDisableSubmit();

registerInfoFirstname.addEventListener('input', validateName);
registerInfoLastname.addEventListener('input', validateName);
registerInfoEmail.addEventListener('input', validateEmail);
registerInfoContactNumber.addEventListener('input', validateContactNumber);
registerInfoAddress.addEventListener('input', validateAddress);