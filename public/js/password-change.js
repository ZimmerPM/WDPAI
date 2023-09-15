var modal = document.getElementById('passwordModal');
var openModalButton = document.getElementById('openPasswordModal');
var closeModalButton = document.getElementById('closePasswordModal');
var changePasswordForm = document.getElementById('changePasswordForm');

// Otwórz okno modalne po kliknięciu przycisku "Zmień hasło"
openModalButton.onclick = function () {
    modal.style.display = 'block';
};

// Zamknij okno modalne po kliknięciu przycisku "Zamknij" (X)
closeModalButton.onclick = function () {
    modal.style.display = 'none';
};

// Zamknij okno modalne po kliknięciu poza obszarem okna modalnego
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};

// Obsługa formularza zmiany hasła
changePasswordForm.onsubmit = function (event) {
    event.preventDefault();
    // Tutaj dodaj logikę zmiany hasła
    // Po udanej zmianie hasła możesz zamknąć okno modalne
    modal.style.display = 'none';
};