document.addEventListener("DOMContentLoaded", function() {
    const addBookModal = document.getElementById("addBookModal");
    const addBookForm = addBookModal.querySelector("form");
    const openAddBookModalButton = document.getElementById("openAddBookModal");
    const closeButton = addBookModal.querySelector(".close-button");
    const messageBox = addBookModal.querySelector(".modal-messageBox");
    const submitButton = addBookForm.querySelector('button[type="submit"]');

    function clearForm(form) {
        for (let element of form.elements) {
            switch (element.type) {
                case 'text':
                case 'number':
                case 'file':
                    element.value = '';
                    break;
            }
        }
    }

    function markValidation(element, condition) {
        if (!condition) {
            element.classList.add('no-valid');
        } else {
            element.classList.remove('no-valid');
        }
    }

    function areFieldsValid(form) {
        let isValid = true;
        for (let element of form.elements) {
            switch (element.type) {
                case 'text':
                case 'number':
                case 'file':
                    if (!element.value) {
                        isValid = false;
                        markValidation(element, false);
                    } else {
                        markValidation(element, true);
                    }
                    break;
            }
        }
        return isValid;
    }

    openAddBookModalButton.addEventListener("click", function() {
        addBookModal.style.display = "block";
        messageBox.innerText = "";
        submitButton.disabled = false; // Włączenie przycisku "Dodaj"
        submitButton.innerText = "Dodaj";
    });

    closeButton.addEventListener("click", function() {
        addBookModal.style.display = "none";
        clearForm(addBookForm);
    });

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && addBookModal.style.display === "block") {
            closeButton.click();
        }
    });

    addBookForm.addEventListener("submit", function(event) {
        event.preventDefault();

        if (!areFieldsValid(addBookForm)) {
            messageBox.innerText = "Proszę o wypełnienie wszystkich pól!";
            return;
        }

        const formData = new FormData(addBookForm);

        fetch('/addBook', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageBox.innerHTML = `<p style="color: green">${data.message}</p>`;

                    // Dezaktywacja przycisku "Dodaj" i zmiana jego napisu
                    submitButton.disabled = true;
                    submitButton.innerText = "Dodano";

                    // Aktywacja możliwości zamknięcia okna klikając poza nim
                    addBookModal.addEventListener("click", function(event) {
                        if (event.target === addBookModal) {
                            closeButton.click();
                        }
                    });
                } else {
                    messageBox.innerHTML = `<p style="color: red">${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});