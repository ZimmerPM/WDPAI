document.addEventListener("DOMContentLoaded", function() {
    const addBookModal = document.getElementById("addBookModal");
    const addBookForm = addBookModal.querySelector("form");
    const openAddBookModalButton = document.getElementById("openAddBookModal");
    const closeButton = addBookModal.querySelector(".close-button");
    const messageBox = addBookModal.querySelector(".modal-messageBox");
    const submitButton = addBookForm.querySelector('button[type="submit"]');

    function clearForm(form) {
        for (let element of form.elements) {
            if (['text', 'number', 'file'].includes(element.type)) {
                element.value = '';
            }
        }
    }

    function markValidation(element, condition) {
        condition ? element.classList.remove('no-valid') : element.classList.add('no-valid');
    }

    function areFieldsValid(form) {
        let isValid = true;
        for (let element of form.elements) {
            if (['text', 'number', 'file'].includes(element.type) && !element.value) {
                isValid = false;
                markValidation(element, false);
            } else {
                markValidation(element, true);
            }
        }
        return isValid;
    }

    openAddBookModalButton.addEventListener("click", function() {
        addBookModal.style.display = "block";
        messageBox.innerText = "";
        submitButton.disabled = false;
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
                    submitButton.disabled = true;
                    submitButton.innerText = "Dodano";
                    addBookModal.addEventListener("click", function(event) {
                        if (event.target === addBookModal) {
                            closeButton.click();
                        }
                    });
                    console.log(data);
                    clearForm(addBookForm);
                    addBookToUI(data.book); // Dodawanie książki do UI
                } else {
                    messageBox.innerHTML = `<p style="color: red">${data.message}</p>`;
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
                    <td>${book.id}</td> <!-- Dodana kolumna Id -->
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