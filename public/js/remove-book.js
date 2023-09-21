document.addEventListener("DOMContentLoaded", function() {
    const deleteBookModal = document.getElementById('deleteBookModal');
    const closeButton = deleteBookModal.querySelector('.close-button-delete');
    const confirmDeleteButton = document.getElementById('confirmDelete');
    const cancelDeleteButton = document.getElementById('cancelDelete');
    const modalMessageBox = deleteBookModal.querySelector('.modal-messageBox');

    function closeDeleteModal() {
        deleteBookModal.style.display = 'none';
        modalMessageBox.innerHTML = ''; // Wyczyszczenie komunikatu
    }

    // Obsługa przycisku zamykania modala
    closeButton.addEventListener('click', closeDeleteModal);
    cancelDeleteButton.addEventListener('click', closeDeleteModal);

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && deleteBookModal.style.display === "block") {
            closeDeleteModal();
        }
    });

    function refreshAfterDelay(delay) {
        setTimeout(() => location.reload(), delay);
    }

    // Dodawanie obsługi dla każdego przycisku "Usuń"
    document.querySelectorAll(".delete-btn").forEach(function(button) {
        button.addEventListener("click", function() {
            const bookId = this.getAttribute("data-id");
            deleteBookModal.style.display = 'block';

            confirmDeleteButton.onclick = function() {
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
                            confirmDeleteButton.disabled = true; // Wyłączenie przycisku potwierdzenia
                            cancelDeleteButton.disabled = true; // Wyłączenie przycisku anulowania

                            // Dodajmy nasłuchiwanie na kliknięcie poza obszarem modalu tylko po sukcesie operacji
                            deleteBookModal.addEventListener("click", function(event) {
                                if (event.target === deleteBookModal) {
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

    deleteBookModal.addEventListener("click", function(event) {
        if (event.target === deleteBookModal && modalMessageBox.innerText.indexOf('success') > -1) {
            closeDeleteModal();
        }
    });
});