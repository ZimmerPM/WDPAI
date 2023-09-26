document.addEventListener("DOMContentLoaded", function () {
    const passwordModal = document.getElementById("passwordModal");
    const openPasswordModalButton = document.getElementById("openPasswordModal");
    const closeButton = passwordModal.querySelector(".close-button");
    const messageBox = passwordModal.querySelector(".modal-messageBox");
    const submitButton = document.getElementById("changePasswordSubmit"); // Dodano odwołanie do przycisku submit

    const newPasswordInput = document.getElementById("newPassword");
    const repeatPasswordInput = document.getElementById("repeatPassword");

    let isChangeSuccess = false; // Dodano zmienną przechowującą stan operacji zmiany hasła

    function resetModal() { // Dodano funkcję resetującą modal
        passwordModal.style.display = "none";
        messageBox.innerText = "";

        document.getElementById("currentPassword").value = "";
        newPasswordInput.value = "";
        repeatPasswordInput.value = "";

        newPasswordInput.classList.remove('no-valid');
        repeatPasswordInput.classList.remove('no-valid');
    }

    openPasswordModalButton.addEventListener("click", function () {
        passwordModal.style.display = "block";
        messageBox.innerText = "";

        document.getElementById("currentPassword").value = "";
        newPasswordInput.value = "";
        repeatPasswordInput.value = "";

        newPasswordInput.classList.remove('no-valid');
        repeatPasswordInput.classList.remove('no-valid');
        submitButton.disabled = false; // Włącz przycisk submit przy otwieraniu modalu
    });

    closeButton.addEventListener("click", resetModal);

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && passwordModal.style.display === "block") {
            resetModal();
        }
    });

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

    function validateNewPasswords() {
        const newPassword = newPasswordInput.value;
        const repeatPassword = repeatPasswordInput.value;

        const passwordsMatch = arePasswordsSame(newPassword, repeatPassword);

        markValidation(newPasswordInput, passwordsMatch);
        markValidation(repeatPasswordInput, passwordsMatch);
    }

    newPasswordInput.addEventListener('keyup', validateNewPasswords);
    repeatPasswordInput.addEventListener('keyup', validateNewPasswords);

    const changePasswordForm = document.getElementById("changePasswordForm");

    changePasswordForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(e.target);

        fetch('/changePassword', {
            method: 'POST',
            body: formData
        })
            .then(handleResponse)
            .then(handleData)
            .catch(handleError);
    });

    function handleResponse(response) {
        if (!response.ok) {
            throw Error(response.statusText);
        }
        return response.json();
    }

    function handleData(data) {
        if (data.success) {
            messageBox.innerHTML = '<p style="color: green">Hasło zostało zmienione!</p>'; // Zastąpiono alert komunikatem w messageBox
            isChangeSuccess = true; // Ustawiono zmienną na true
            submitButton.disabled = true; // Dezaktywowanie przycisku submit
        } else {
            messageBox.innerText = data.message;
        }
    }

    function handleError(error) {
        messageBox.innerText = "Wystąpił nieoczekiwany błąd.";
    }

    // Listener do zamykania modalu przez kliknięcie poza jego obszarem
    window.addEventListener('click', (event) => {
        if (event.target === passwordModal && isChangeSuccess) {
            resetModal();
            isChangeSuccess = false; // Resetowanie zmiennej
        }
    });
});