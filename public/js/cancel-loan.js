document.addEventListener('DOMContentLoaded', () => {
    const userRole = document.body.getAttribute('data-role');
    if (userRole !== 'admin') return;

    const cancelLoanModal = document.getElementById('cancelLoanModalAdmin');
    const closeBtn = cancelLoanModal.querySelector('.close-button');
    const confirmBtn = document.getElementById('adminConfirmCancelLoan');
    const cancelBtn = document.getElementById('adminCancelCancelLoan');
    const loanTitleSpan = document.getElementById('cancelLoanBookTitle');
    const userNameSpan = document.getElementById('cancelLoanUserName');
    const messageBox = cancelLoanModal.querySelector('.modal-messageBox');
    const loansTableBodyAdmin = document.getElementById('loansTableBodyAdmin');

    let loanId;
    let isCancellationSuccess = false;

    const resetModal = () => {
        messageBox.innerHTML = '';
        confirmBtn.disabled = false;
        cancelBtn.disabled = false;
        isCancellationSuccess = false;
    };

    const checkAndUpdateTable = () => {
        if (loansTableBodyAdmin.querySelectorAll('tr').length === 0) {
            loansTableBodyAdmin.innerHTML = `
                <tr>
                    <td colspan="8" class="no-results-message" id="loans-table-message">Tabela wypożyczeń jest pusta</td>
                </tr>`;
        }
    };

    document.querySelectorAll('.loans-management-buttons.cancel-button').forEach(button => {
        button.addEventListener('click', () => {
            loanId = button.getAttribute('data-loan-id');
            const loanTitle = button.closest('tr').querySelector('td:nth-child(5)').textContent;
            const userName = button.closest('tr').querySelector('td:nth-child(2)').textContent;

            loanTitleSpan.textContent = loanTitle;
            userNameSpan.textContent = userName;
            cancelLoanModal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', () => {
        cancelLoanModal.style.display = 'none';
        resetModal();
    });

    cancelBtn.addEventListener('click', () => {
        cancelLoanModal.style.display = 'none';
        resetModal();
    });

    confirmBtn.addEventListener('click', () => {
        fetch('/cancelLoan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ loanId: loanId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageBox.innerHTML = `<p style="color: green">Wypożyczenie zostało anulowane!</p>`;
                    confirmBtn.disabled = true;
                    cancelBtn.disabled = true;
                    isCancellationSuccess = true;

                    const loanRow = document.querySelector(`[data-loan-id="${loanId}"]`).closest('tr');
                    if (loanRow) {
                        loanRow.remove();
                    }

                    checkAndUpdateTable();
                } else {
                    messageBox.innerHTML = `<p style="color: red">Wystąpił błąd podczas anulowania wypożyczenia.</p>`;
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                messageBox.innerHTML = `<p style="color: red">Wystąpił błąd. Spróbuj ponownie później.</p>`;
            });
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            cancelLoanModal.style.display = 'none';
            resetModal();
        }
    });

    window.addEventListener('click', (event) => {
        if (event.target === cancelLoanModal && isCancellationSuccess) {
            cancelLoanModal.style.display = 'none';
            resetModal();
        }
    });
});
