document.addEventListener('DOMContentLoaded', () => {
    const userRole = document.body.getAttribute('data-role');
    if (userRole !== 'user') return;

    const cancelModal = document.getElementById('cancelModal');
    const closeBtn = cancelModal.querySelector('.close-button');
    const confirmBtn = document.getElementById('confirmCancel');
    const cancelBtn = document.getElementById('cancelCancel');
    const reservationTitleSpan = document.getElementById('reservationTitle');
    const messageBox = cancelModal.querySelector('.modal-messageBox');
    const reservationsTableBodyUser = document.getElementById('reservationsTableBodyUser');

    let reservationId;
    let isCancellationSuccess = false;

    const resetModal = () => {
        messageBox.innerHTML = '';
        confirmBtn.disabled = false;
        cancelBtn.disabled = false;
        isCancellationSuccess = false;
    };

    const checkAndUpdateTable = () => {
        if (reservationsTableBodyUser.querySelectorAll('tr').length === 0) {
            reservationsTableBodyUser.innerHTML = `
                <tr>
                    <td colspan="6" class="no-results-message" id="reservations-table-message">Tabela rezerwacji jest pusta</td>
                </tr>`;
        }
    };

    document.querySelectorAll('.reservations-management-buttons').forEach(button => {
        button.addEventListener('click', () => {
            reservationId = button.getAttribute('data-reservation-id');
            const reservationTitle = button.closest('tr').querySelector('td:nth-child(3)').textContent;

            reservationTitleSpan.textContent = reservationTitle;
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
        fetch('/cancelReservation', {
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

                    const reservationRow = document.querySelector(`.reservations-management-buttons[data-reservation-id="${reservationId}"]`).closest('tr');
                    if (reservationRow) {
                        reservationRow.remove();
                    }

                    checkAndUpdateTable();
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

    checkAndUpdateTable(); // Sprawdzaj tabelę po załadowaniu strony
});
