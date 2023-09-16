const form = document.querySelector("form");
const emailInput = form.querySelector('input[name="email"]');
const passwordInput = form.querySelector('input[name="password"]');
const confirmPasswordInput = form.querySelector('input[name="confirm-password"]');

function isEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}

function arePasswordsSame(password, confirmPassword) {
    return password === confirmPassword;
}

function markValidation(element, condition) {
    if (!condition) {
        element.classList.add('no-valid');
    } else {
        element.classList.remove('no-valid');
    }
}

function validateEmail() {
    markValidation(emailInput, isEmail(emailInput.value));
}

function validatePasswords() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;

    const passwordsMatch = arePasswordsSame(password, confirmPassword);

    markValidation(passwordInput, passwordsMatch);
    markValidation(confirmPasswordInput, passwordsMatch);
}

emailInput.addEventListener('keyup', validateEmail);
passwordInput.addEventListener('keyup', validatePasswords);
confirmPasswordInput.addEventListener('keyup', validatePasswords);
