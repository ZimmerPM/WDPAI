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
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length) {
                    alert(data.messages.join(", "));
                } else if (data.book) {
                    alert("Książka dodana pomyślnie!");
                    addBookModal.style.display = "none";
                    clearForm(addBookForm);
                    addBookToUI(data.book);
                }
            })
            .catch(error => console.error('Error:', error));
    });

    function addBookToUI(book) {
        const booksContainer = document.querySelector('.books-container');
        let bookDiv = document.createElement('div');
        bookDiv.className = 'book-entry';

        bookDiv.innerHTML = `
        <div class="book-cover">
            <img src="${book.image}" alt="${book.title}">
        </div>
        <table class="catalog-table">
            <tbody>
                <tr>
                    <td>${book.title}</td>
                    <td>${book.author}</td>
                    <td>${book.publicationyear}</td>
                    <td>${book.genre}</td>
                    <td>${book.availability}</td>
                    <td>${book.stock}</td>
                    <td>
                        <div class="btn-container">
                            <button>Edytuj</button>
                            <button>Usuń</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    `;

        booksContainer.appendChild(bookDiv);
    }
});