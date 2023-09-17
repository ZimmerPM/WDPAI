document.querySelector(".search-button").addEventListener("click", searchFunction);
document.querySelector(".search-input").addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        searchFunction();
    }
});

function searchFunction() {
    let query = document.querySelector(".search-input").value;

    fetch('/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ query: query })
    })
        .then(response => response.json())
        .then(data => {
            let bookContainer = document.querySelector(".books-container");
            bookContainer.innerHTML = "";

            if (!data.books || data.books.length === 0) {
                let noResultsDiv = document.createElement("div");
                noResultsDiv.textContent = "Brak wyników wyszukiwania";
                noResultsDiv.className = 'no-results-message';
                bookContainer.appendChild(noResultsDiv);
                return;
            }

            data.books.forEach(book => {
                let bookDiv = document.createElement("div");
                bookDiv.className = "book-entry";

                let bookCover = document.createElement("div");
                bookCover.className = "book-cover";
                let bookImage = document.createElement("img");
                bookImage.src = book.image;
                bookImage.alt = book.title;
                bookCover.appendChild(bookImage);

                let bookTable = document.createElement("table");
                bookTable.className = "catalog-table";

                let bookTBody = document.createElement("tbody");
                let bookRow = document.createElement("tr");

                let titleCell = document.createElement("td");
                titleCell.textContent = book.title;
                let authorCell = document.createElement("td");
                authorCell.textContent = book.author;
                let yearCell = document.createElement("td");
                yearCell.textContent = book.publicationyear;
                let genreCell = document.createElement("td");
                genreCell.textContent = book.genre;
                let availabilityCell = document.createElement("td");
                availabilityCell.textContent = book.availability ? 'Dostępna' : 'Niedostępna';
                let stockCell = document.createElement("td");
                stockCell.textContent = book.stock;

                bookRow.appendChild(titleCell);
                bookRow.appendChild(authorCell);
                bookRow.appendChild(yearCell);
                bookRow.appendChild(genreCell);
                bookRow.appendChild(availabilityCell);
                bookRow.appendChild(stockCell);

                // Dodajemy kolumnę "Operacje" tylko jeżeli użytkownik jest zalogowany
                if (data.isLoggedIn) {
                    let operationCell = document.createElement("td");
                    let btnContainer = document.createElement("div");
                    btnContainer.className = "btn-container";

                    let operationButton = document.createElement("button");
                    operationButton.textContent = "Wypożycz";
                    if (!book.availability) {
                        operationButton.disabled = true;
                    }
                    btnContainer.appendChild(operationButton);

                    let reserveButton = document.createElement("button");
                    reserveButton.textContent = "Rezerwuj";
                    btnContainer.appendChild(reserveButton);

                    operationCell.appendChild(btnContainer);
                    bookRow.appendChild(operationCell);
                }

                bookTBody.appendChild(bookRow);
                bookTable.appendChild(bookTBody);

                bookDiv.appendChild(bookCover);
                bookDiv.appendChild(bookTable);

                bookContainer.appendChild(bookDiv);
            });
        });
}