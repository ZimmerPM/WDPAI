document.addEventListener("DOMContentLoaded", function() {
    const addBookModal = document.getElementById("addBookModal");
    const addBookForm = addBookModal.querySelector("form");
    const openAddBookModalButton = document.getElementById("openAddBookModal");
    const closeButton = document.querySelector(".close-button");

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

    openAddBookModalButton.addEventListener("click", function() {
        addBookModal.style.display = "block";
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