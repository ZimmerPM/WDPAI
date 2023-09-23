document.addEventListener('DOMContentLoaded', () => {
    const userRole = document.body.getAttribute('data-role');
    if (userRole !== 'admin') return; // Sprawdzamy, czy zalogowany użytkownik jest administratorem

    // Używamy nowego modalu dla admina
    const cancelModal = document.getElementById('cancelModalAdmin');
    const closeBtn = cancelModal.querySelector('.close-button');
    const confirmBtn = document.getElementById('adminConfirmCancel');
    const cancelBtn = document.getElementById('adminCancelCancel');
    const reservationTitleSpan = document.getElementById('adminReservationTitle');
    const userNameSpan = document.getElementById('userName'); // Span dla imienia i nazwiska użytkownika
    const messageBox = cancelModal.querySelector('.modal-messageBox');

    let reservationId;
    let isCancellationSuccess = false;

    const resetModal = () => {
        messageBox.innerHTML = '';
        confirmBtn.disabled = false;
        cancelBtn.disabled = false;
        isCancellationSuccess = false;
    };

    document.querySelectorAll('.reservations-management-buttons.cancel-button').forEach(button => {
        button.addEventListener('click', () => {
            reservationId = button.getAttribute('data-reservation-id');
            const reservationTitle = button.closest('tr').querySelector('td:nth-child(5)').textContent;
            const userName = button.closest('tr').querySelector('td:nth-child(2)').textContent;

            reservationTitleSpan.textContent = reservationTitle;
            userNameSpan.textContent = userName; // Ustawiamy imię i nazwisko użytkownika
            cancelModal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', () => {
        cancelModal.style.display = 'none';
        resetModal();
    });

    cancelBtn.addEventListener('click', () => {
        cancelModal.style.display = 'none';
        resetModal();
    });

    confirmBtn.addEventListener('click', () => {
        fetch('adminCancelReservation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ reservationId: reservationId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageBox.innerHTML = `<p style="color: green">Rezerwacja została anulowana!</p>`;
                    confirmBtn.disabled = true;
                    cancelBtn.disabled = true;
                    isCancellationSuccess = true;

                    // Usuń wiersz z tabeli
                    const reservationRow = document.querySelector(`.reservations-management-buttons[data-reservation-id="${reservationId}"]`).closest('tr');
                    if (reservationRow) {
                        reservationRow.remove();
                    }
                } else {
                    messageBox.innerHTML = `<p style="color: red">Wystąpił błąd podczas anulowania rezerwacji.</p>`;
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                messageBox.innerHTML = `<p style="color: red">Wystąpił błąd. Spróbuj ponownie później.</p>`;
            });
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            cancelModal.style.display = 'none';
            resetModal();
        }
    });

    window.addEventListener('click', (event) => {
        if (event.target === cancelModal && isCancellationSuccess) {
            cancelModal.style.display = 'none';
            resetModal();
        }
    });
});
