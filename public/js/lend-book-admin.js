document.addEventListener('DOMContentLoaded', () => {
    const userRole = document.body.getAttribute('data-role');
    if (userRole !== 'admin') return;

    const lendModal = document.getElementById('lendModalAdmin');
    const closeBtn = lendModal.querySelector('.close-button');
    const confirmBtn = document.getElementById('adminConfirmLend');
    const cancelBtn = document.getElementById('adminCancelLend');
    const lendTitleSpan = document.getElementById('adminLendTitle');
    const userNameSpan = document.getElementById('lendUserName');
    const messageBox = lendModal.querySelector('.modal-messageBox');
    const reservationsTableBodyAdmin = document.getElementById('reservationsTableBodyAdmin');

    let reservationId;
    let isLendSuccess = false;

    const resetModal = () => {
        messageBox.innerHTML = '';
        confirmBtn.disabled = false;
        cancelBtn.disabled = false;
        isLendSuccess = false;
    };

    const checkAndUpdateTable = () => {
        if (reservationsTableBodyAdmin.querySelectorAll('tr').length === 0) {
            reservationsTableBodyAdmin.innerHTML = `
                <tr>
                    <td colspan="8" class="no-results-message" id="reservations-table-message">Tabela rezerwacji jest pusta</td>
                </tr>`;
        }
    };

    document.querySelectorAll('.reservations-management-buttons.lend-button').forEach(button => {
        button.addEventListener('click', () => {
            reservationId = button.getAttribute('data-reservation-id');
            const lendTitle = button.closest('tr').querySelector('td:nth-child(5)').textContent;
            const userName = button.closest('tr').querySelector('td:nth-child(2)').textContent;

            lendTitleSpan.textContent = lendTitle;
            userNameSpan.textContent = userName;
            lendModal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', () => {
        lendModal.style.display = 'none';
        resetModal();
    });

    cancelBtn.addEventListener('click', () => {
        lendModal.style.display = 'none';
        resetModal();
    });

    confirmBtn.addEventListener('click', () => {
        fetch('/lendBook', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ reservationId: reservationId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageBox.innerHTML = `<p style="color: green">Książka została wypożyczona!</p>`;
                    confirmBtn.disabled = true;
                    cancelBtn.disabled = true;
                    isLendSuccess = true;

                    const reservationRow = document.querySelector(`.reservations-management-buttons[data-reservation-id="${reservationId}"]`).closest('tr');
                    if (reservationRow) {
                        reservationRow.remove();
                    }

                    checkAndUpdateTable();
                } else {
                    messageBox.innerHTML = `<p style="color: red">Wystąpił błąd podczas wypożyczania książki.</p>`;
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                messageBox.innerHTML = `<p style="color: red">Wystąpił błąd. Spróbuj ponownie później.</p>`;
            });
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            lendModal.style.display = 'none';
            resetModal();
        }
    });

    window.addEventListener('click', (event) => {
        if (event.target === lendModal && isLendSuccess) {
            lendModal.style.display = 'none';
            resetModal();
        }
    });
});
