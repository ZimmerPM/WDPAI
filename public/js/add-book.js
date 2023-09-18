document.addEventListener("DOMContentLoaded", function() {
    const addBookModal = document.getElementById("addBookModal");
    const addBookForm = addBookModal.querySelector("form");
    const openAddBookModalButton = document.getElementById("openAddBookModal");
    const closeButton = document.querySelector(".close-button");
    const messageBox = document.querySelector(".modal-messageBox");  // Zaktualizowana referencja do messageBox

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

    function areFieldsValid() {
        const author = addBookForm.querySelector("input[name='author']").value;
        const title = addBookForm.querySelector("input[name='title']").value;
        const stock = addBookForm.querySelector("input[name='stock']").value;

        return author && title && stock;  // Sprawdź, czy wszystkie pola mają wartość
    }

    openAddBookModalButton.addEventListener("click", function() {
        addBookModal.style.display = "block";
        messageBox.innerText = "";  // Wyczyść messageBox przy otwarciu okna
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

        if (!areFieldsValid()) {
            messageBox.innerText = "Proszę wypełnić wszystkie obowiązkowe pola!";
            return;
        }

        const formData = new FormData(addBookForm);

        fetch('/addBook', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => handleResponse(data))
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