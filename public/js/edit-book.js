document.addEventListener("DOMContentLoaded", function() {
    const editModal = document.getElementById("editBookModal");
    const editForm = editModal.querySelector("form");
    const hiddenFilePathInput = document.getElementById("hiddenFilePath");
    const editBookIdField = document.getElementById('editBookId');
    const messageBox = editModal.querySelector(".modal-messageBox");
    const submitButton = editForm.querySelector('button[type="submit"]');
    const closeButton = editModal.querySelector(".close-button");

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

    closeButton.addEventListener("click", function() {
        editModal.style.display = "none";
        clearForm(editForm);
    });

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && editModal.style.display === "block") {
            closeButton.click();
        }
    });

    function updateEditForm(button) {
        const bookId = button.getAttribute("data-id");
        const title = button.getAttribute("data-title");
        const author = button.getAttribute("data-author");
        const publicationYear = button.getAttribute("data-publicationyear");
        const genre = button.getAttribute("data-genre");
        const stock = button.getAttribute("data-stock");
        const image = button.getAttribute("data-image");

        editBookIdField.value = bookId;
        editForm.querySelector("[name='title']").value = title;
        editForm.querySelector("[name='author']").value = author;
        editForm.querySelector("[name='publicationyear']").value = publicationYear;
        editForm.querySelector("[name='genre']").value = genre;
        editForm.querySelector("[name='stock']").value = stock;
        hiddenFilePathInput.value = image;
    }

    const booksContainer = document.querySelector(".books-container");
    if (booksContainer) {
        booksContainer.addEventListener("click", function(event) {
            const button = event.target.closest(".edit-btn");
            if (button && booksContainer.contains(button)) {
                clearForm(editForm);
                updateEditForm(button); // Aktualizuje formularz danymi z klikniętego rekordu.
                editModal.style.display = "block";
                messageBox.innerText = "";
                submitButton.disabled = false;
                submitButton.innerText = "Aktualizuj";
            }
        });
    }

    editForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(editForm);

        fetch('/editBook', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageBox.innerHTML = `<p style="color: green">${data.message}</p>`;
                    submitButton.disabled = true;
                    submitButton.innerText = "Wykonano";

                    const bookId = editBookIdField.value;
                    const editedEntry = document.querySelector(`.edit-btn[data-id="${bookId}"]`).closest('.book-entry');

                    editedEntry.querySelector(`td:nth-child(2)`).textContent = formData.get("title");
                    editedEntry.querySelector(`td:nth-child(3)`).textContent = formData.get("author");
                    editedEntry.querySelector(`td:nth-child(4)`).textContent = formData.get("publicationyear");
                    editedEntry.querySelector(`td:nth-child(5)`).textContent = formData.get("genre");
                    editedEntry.querySelector(`td:nth-child(7)`).textContent = formData.get("stock");

                    const newImagePath = data.book.image;
                    editedEntry.querySelector('.book-cover img').src = newImagePath;

                    editModal.addEventListener("click", function(event) {
                        if (event.target === editModal) {
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
