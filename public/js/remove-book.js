document.addEventListener("DOMContentLoaded", function() {
    const deleteBookModal = document.getElementById('deleteBookModal');
    const closeButton = deleteBookModal.querySelector('.close-button-delete');
    const confirmDeleteButton = document.getElementById('confirmDelete');
    const cancelDeleteButton = document.getElementById('cancelDelete');
    const modalMessageBox = deleteBookModal.querySelector('.modal-messageBox');

    let bookId;

    function closeDeleteModal() {
        deleteBookModal.style.display = 'none';
        modalMessageBox.innerHTML = '';
        confirmDeleteButton.disabled = false;
        cancelDeleteButton.disabled = false;
    }

    closeButton.addEventListener('click', closeDeleteModal);
    cancelDeleteButton.addEventListener('click', closeDeleteModal);

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && deleteBookModal.style.display === "block") {
            closeDeleteModal();
        }
    });

    document.querySelectorAll(".delete-btn").forEach(function(button) {
        button.addEventListener("click", function() {
            bookId = this.getAttribute("data-id");
            deleteBookModal.style.display = 'block';
        });
    });

    confirmDeleteButton.addEventListener("click", function() {
        fetch('/removeBook', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: bookId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    modalMessageBox.innerHTML = `<p style="color: green">${data.message}</p>`;
                    confirmDeleteButton.disabled = true;
                    cancelDeleteButton.disabled = true;

                    // Usuń cały element book-entry
                    const bookEntry = document.querySelector(`.delete-btn[data-id="${bookId}"]`).closest('.book-entry');
                    if (bookEntry) {
                        bookEntry.remove();
                    }

                    deleteBookModal.addEventListener("click", function(event) {
                        if (event.target === deleteBookModal) {
                            closeDeleteModal();
                        }
                    });
                } else {
                    modalMessageBox.innerHTML = `<p style="color: red">${data.message}</p>`;
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                modalMessageBox.innerHTML = `<p style="color: red">Wystąpił błąd. Spróbuj ponownie później.</p>`;
            });
    });

    deleteBookModal.addEventListener("click", function(event) {
        if (event.target === deleteBookModal && modalMessageBox.innerText.indexOf('success') > -1) {
            closeDeleteModal();
        }
    });
});
