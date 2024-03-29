document.addEventListener('DOMContentLoaded', () => {
    const reserveModal = document.getElementById('reserveModal');
    const closeBtn = document.querySelector('.close-button');
    const confirmBtn = document.getElementById('confirmReserve');
    const cancelBtn = document.getElementById('cancelReserve');
    const bookTitleSpan = document.getElementById('bookTitle');
    const messageBox = document.querySelector('.modal-messageBox');

    let bookId;
    let isReservationSuccess = false;

    const resetModal = () => {
        messageBox.innerHTML = '';
        confirmBtn.disabled = false;
        cancelBtn.disabled = false;
        isReservationSuccess = false;
    };

    function borrowButtonClick() {
        bookId = this.getAttribute('data-book-id');
        const bookTitle = this.getAttribute('data-book-title');
        bookTitleSpan.textContent = bookTitle;
        reserveModal.style.display = 'block';
    }

    function assignBorrowEvent() {
        document.querySelectorAll('.borrow-btn').forEach(button => {
            button.removeEventListener('click', borrowButtonClick);
            button.addEventListener('click', borrowButtonClick);
        });
    }

    window.assignBorrowEvent = assignBorrowEvent;
    assignBorrowEvent();

    closeBtn.addEventListener('click', () => {
        reserveModal.style.display = 'none';
        resetModal();
    });

    cancelBtn.addEventListener('click', () => {
        reserveModal.style.display = 'none';
        resetModal();
    });

    confirmBtn.addEventListener('click', () => {
        fetch('/reserve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ bookId: bookId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageBox.innerHTML = `<p style="color: green">Książka została zarezerwowana!</p>`;
                    isReservationSuccess = true;
                    confirmBtn.disabled = true;
                    cancelBtn.disabled = true;

                    const borrowBtn = document.querySelector(`.borrow-btn[data-book-id="${bookId}"]`);
                    if (borrowBtn) {
                        borrowBtn.disabled = true;
                    }

                    const bookEntry = document.querySelector(`.book-entry[data-id="${bookId}"]`);
                    if (bookEntry) {
                        const stockCell = bookEntry.querySelector('td:nth-child(6)');
                        if (stockCell) {
                            const currentStock = Number(stockCell.textContent);
                            if (!isNaN(currentStock)) {
                                const newStock = currentStock - 1;
                                stockCell.textContent = newStock;
                                if (newStock === 0) {
                                    const availabilityCell = bookEntry.querySelector('td:nth-child(5)');
                                    if (availabilityCell) {
                                        availabilityCell.textContent = 'Niedostępna';
                                    }
                                }
                            }
                        }
                    }

                } else {
                    messageBox.innerHTML = `<p style="color: red">Wystąpił błąd podczas rezerwacji książki.</p>`;
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                messageBox.innerHTML = `<p style="color: red">Wystąpił błąd. Spróbuj ponownie później.</p>`;
            });
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            reserveModal.style.display = 'none';
            resetModal();
        }
    });

    window.addEventListener('click', (event) => {
        if (event.target === reserveModal && isReservationSuccess) {
            reserveModal.style.display = 'none';
            resetModal();
        }
    });
});
