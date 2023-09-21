document.addEventListener("DOMContentLoaded", function() {
    const deleteUserModal = document.getElementById('deleteUserModal');
    const closeButton = deleteUserModal.querySelector('.close-button-delete-user');
    const confirmDeleteButton = document.getElementById('confirmDeleteUser');
    const cancelDeleteButton = document.getElementById('cancelDeleteUser');
    const modalMessageBox = deleteUserModal.querySelector('.modal-messageBox');

    function closeDeleteModal() {
        deleteUserModal.style.display = 'none';
        modalMessageBox.innerHTML = ''; // Wyczyszczenie komunikatu
    }

    // Obsługa przycisku zamykania modala
    closeButton.addEventListener('click', closeDeleteModal);
    cancelDeleteButton.addEventListener('click', closeDeleteModal);

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && deleteUserModal.style.display === "block") {
            closeDeleteModal();
        }
    });

    function refreshAfterDelay(delay) {
        setTimeout(() => location.reload(), delay);
    }

    // Dodawanie obsługi dla każdego przycisku "Usuń" dla użytkownika
    document.querySelectorAll('button[data-action="delete"]').forEach(function(button) {
        button.addEventListener("click", function() {
            const userId = this.getAttribute("data-id");
            deleteUserModal.style.display = 'block';

            confirmDeleteButton.onclick = function() {
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

                            // Dodajmy nasłuchiwanie na kliknięcie poza obszarem modalu tylko po sukcesie operacji
                            deleteUserModal.addEventListener("click", function(event) {
                                if (event.target === deleteUserModal) {
                                    closeDeleteModal();
                                    refreshAfterDelay(1000); // odświeżenie strony po 1 sekundzie od zamknięcia modalu
                                }
                            });

                            refreshAfterDelay(3000); // domyślne odświeżenie strony po 3 sekundach
                        } else {
                            modalMessageBox.innerHTML = `<p style="color: red">${data.message}</p>`;
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            };
        });
    });

    deleteUserModal.addEventListener("click", function(event) {
        if (event.target === deleteUserModal && modalMessageBox.innerText.indexOf('success') > -1) {
            closeDeleteModal();
        }
    });
});