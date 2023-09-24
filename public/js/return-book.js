document.addEventListener('DOMContentLoaded', () => {
    const userRole = document.body.getAttribute('data-role');
    if (userRole !== 'admin') return;

    const returnModal = document.getElementById('returnModalAdmin');
    const closeBtn = returnModal.querySelector('.close-button');
    const confirmBtn = document.getElementById('adminConfirmReturn');
    const cancelBtn = document.getElementById('adminCancelReturn');
    const returnTitleSpan = document.getElementById('returnBookTitle');
    const userNameSpan = document.getElementById('returnUserName');
    const messageBox = returnModal.querySelector('.modal-messageBox');
    const loansTableBodyAdmin = document.getElementById('loansTableBodyAdmin');

    let loanId;
    let isReturnSuccess = false;

    const resetModal = () => {
        messageBox.innerHTML = '';
        confirmBtn.disabled = false;
        cancelBtn.disabled = false;
        isReturnSuccess = false;
    };

    const checkAndUpdateTable = () => {
        if (loansTableBodyAdmin.querySelectorAll('tr').length === 0) {
            loansTableBodyAdmin.innerHTML = `
                <tr>
                    <td colspan="8" class="no-results-message" id="loans-table-message-admin">Tabela wypożyczeń jest pusta</td>
                </tr>`;
        }
    };

    document.querySelectorAll('.loans-management-buttons.return-button').forEach(button => {
        button.addEventListener('click', () => {
            loanId = button.getAttribute('data-loan-id');
            const returnTitle = button.closest('tr').querySelector('td:nth-child(5)').textContent;
            const userName = button.closest('tr').querySelector('td:nth-child(2)').textContent;

            returnTitleSpan.textContent = returnTitle;
            userNameSpan.textContent = userName;
            returnModal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', () => {
        returnModal.style.display = 'none';
        resetModal();
    });

    cancelBtn.addEventListener('click', () => {
        returnModal.style.display = 'none';
        resetModal();
    });

    confirmBtn.addEventListener('click', () => {
        fetch('/returnBook', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ loanId: loanId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageBox.innerHTML = `<p style="color: green">Książka została zwrócona!</p>`;
                    confirmBtn.disabled = true;
                    cancelBtn.disabled = true;
                    isReturnSuccess = true;

                    const loanRow = document.querySelector(`.loans-management-buttons[data-loan-id="${loanId}"]`).closest('tr');
                    if (loanRow) {
                        loanRow.remove();
                    }

                    checkAndUpdateTable();
                } else {
                    messageBox.innerHTML = `<p style="color: red">Wystąpił błąd podczas zwracania książki.</p>`;
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                messageBox.innerHTML = `<p style="color: red">Wystąpił błąd. Spróbuj ponownie później.</p>`;
            });
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            returnModal.style.display = 'none';
            resetModal();
        }
    });

    window.addEventListener('click', (event) => {
        if (event.target === returnModal && isReturnSuccess) {
            returnModal.style.display = 'none';
            resetModal();
        }
    });
});