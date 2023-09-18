document.addEventListener("DOMContentLoaded", function() {
    const addBookModal = document.getElementById("addBookModal");
    const addBookForm = addBookModal.querySelector("form");
    const openAddBookModalButton = document.getElementById("openAddBookModal");
    const closeButton = document.querySelector(".close-button");
    const messageBox = document.querySelector(".modal-messageBox");

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
    });

    closeButton.addEventListener("click", function() {
        addBookModal.style.display = "none";
        clearForm(addBookForm);
    });

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && addBookModal.style.display === "block") {
            addBookModal.style.display = "none";
            clearForm(addBookForm);
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
            .then(response => {
                return response.text(); // Pobierz zawartość odpowiedzi jako tekst
            })
            .then(data => {
                console.log(data); // Wyświetl zawartość odpowiedzi jako tekst
                try {
                    const jsonData = JSON.parse(data); // Spróbuj sparsować tekst jako JSON
                    handleResponse(jsonData); // Przetwórz jako JSON, jeśli się powiedzie
                } catch (error) {
                    console.error('Error parsing JSON:', error); // Obsłuż błąd parsowania JSON
                }
            })
            .catch(error => console.error('Error:', error));
    });

    function handleResponse(data) {
        if (data.messages && data.messages.length) {
            alert(data.messages.join(", "));
        } else if (data.book) {
            alert("Książka dodana pomyślnie!");
            addBookModal.style.display = "none";
            clearForm(addBookForm);
        }
    }
});