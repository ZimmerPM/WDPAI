document.addEventListener("DOMContentLoaded", function() {
    const deleteUserModal = document.getElementById('deleteUserModal');
    const closeButton = deleteUserModal.querySelector('.close-button-delete-user');
    const confirmDeleteButton = document.getElementById('confirmDeleteUser');
    const cancelDeleteButton = document.getElementById('cancelDeleteUser');
    const modalMessageBox = deleteUserModal.querySelector('.modal-messageBox');

    let userId;

    function closeDeleteModal() {
        deleteUserModal.style.display = 'none';
        modalMessageBox.innerHTML = ''; // Wyczyszczenie komunikatu
        confirmDeleteButton.disabled = false;
        cancelDeleteButton.disabled = false;
    }

    // Obsługa przycisku zamykania modala
    closeButton.addEventListener('click', closeDeleteModal);
    cancelDeleteButton.addEventListener('click', closeDeleteModal);

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && deleteUserModal.style.display === "block") {
            closeDeleteModal();
        }
    });

    // Dodawanie obsługi dla każdego przycisku "Usuń" dla użytkownika
    document.querySelectorAll('button[data-action="delete"]').forEach(function(button) {
        button.addEventListener("click", function() {
            userId = this.getAttribute("data-id");
            deleteUserModal.style.display = 'block';
        });
    });

    confirmDeleteButton.addEventListener("click", function() {
        fetch('/removeUser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: userId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    modalMessageBox.innerHTML = `<p style="color: green">${data.message}</p>`;
                    confirmDeleteButton.disabled = true; // Wyłączenie przycisku potwierdzenia
                    cancelDeleteButton.disabled = true; // Wyłączenie przycisku anulowania

                    // Usunięcie wiersza z tabeli
                    const userRow = document.querySelector(`button[data-id="${userId}"]`).closest('tr');
                    if (userRow) {
                        userRow.remove();
                    }

                    deleteUserModal.addEventListener("click", function(event) {
                        if (event.target === deleteUserModal) {
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

    deleteUserModal.addEventListener("click", function(event) {
        if (event.target === deleteUserModal && modalMessageBox.innerText.indexOf('success') > -1) {
            closeDeleteModal();
        }
    });
});
