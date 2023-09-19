document.addEventListener("DOMContentLoaded", function() {

    // Pobranie wszystkich przycisków "Edytuj"
    let editButtons = document.querySelectorAll(".edit-btn");

    // Pobranie modala edycji
    let editModal = document.getElementById("editBookModal");

    // Pobranie przycisku zamykającego modal
    let closeModalButton = editModal.querySelector(".close-button");

    // Funkcja otwierająca modal
    function openModal() {
        editModal.style.display = "block";
    }


    // Funkcja zamykająca modal
    function closeModal() {
        editModal.style.display = "none";
    }

    // Dodanie event listenera do każdego przycisku "Edytuj"
    editButtons.forEach(function(button) {
        button.addEventListener("click", openModal);
    });

    // Dodanie event listenera do przycisku zamykającego modal
    closeModalButton.addEventListener("click", closeModal);

    // Zamykanie modala po kliknięciu poza jego treść
    window.addEventListener("click", function(event) {
        if (event.target === editModal) {
            closeModal();
        }
    });

});