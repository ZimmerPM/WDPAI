document.addEventListener("DOMContentLoaded", function() {
    const passwordModal = document.getElementById("passwordModal");
    const openPasswordModalButton = document.getElementById("openPasswordModal");
    const closeButton = document.querySelector(".close-button");
    const messageBox = document.getElementById("messageBox");

    const newPasswordInput = document.getElementById("newPassword");
    const repeatPasswordInput = document.getElementById("repeatPassword");

    openPasswordModalButton.addEventListener("click", function() {
        passwordModal.style.display = "block";
        messageBox.innerText = ""; // Wyczyść messageBox przy otwarciu okna

        document.getElementById("currentPassword").value = "";
        document.getElementById("newPassword").value = "";
        document.getElementById("repeatPassword").value = "";

        newPasswordInput.classList.remove('no-valid');
        repeatPasswordInput.classList.remove('no-valid');

    });

    closeButton.addEventListener("click", function() {
        passwordModal.style.display = "none";
        messageBox.innerText = ""; // Wyczyść messageBox przy zamknięciu okna


        document.getElementById("currentPassword").value = "";
        document.getElementById("newPassword").value = "";
        document.getElementById("repeatPassword").value = "";

        newPasswordInput.classList.remove('no-valid');
        repeatPasswordInput.classList.remove('no-valid');
    });

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && passwordModal.style.display === "block") {
            passwordModal.style.display = "none";
            messageBox.innerText = ""; // Wyczyść messageBox przy zamknięciu okna

            document.getElementById("currentPassword").value = "";
            document.getElementById("newPassword").value = "";
            document.getElementById("repeatPassword").value = "";

            newPasswordInput.classList.remove('no-valid');
            repeatPasswordInput.classList.remove('no-valid');
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

    changePasswordForm.addEventListener("submit", function(e) {
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
            alert("Hasło zostało zmienione!");
            passwordModal.style.display = "none";
            messageBox.innerText = ""; // Wyczyść messageBox po udanej zmianie hasła
        } else {
            messageBox.innerText = data.message;
        }
    }

    function handleError(error) {
        messageBox.innerText = "Wystąpił nieoczekiwany błąd.";
    }
});