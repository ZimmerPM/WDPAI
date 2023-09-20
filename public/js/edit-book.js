const editModal = document.getElementById("editBookModal");
const editForm = editModal.querySelector("form");
const hiddenFilePathInput = document.getElementById("hiddenFilePath");
const editBookIdField = document.getElementById('editBookId');
const messageBox = editModal.querySelector(".modal-messageBox");

// Nasłuchiwanie na przycisk edycji
document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", function() {


        // Ustawienie wartości w formularzu z danych przycisku
        const title = this.getAttribute("data-title");
        const author = this.getAttribute("data-author");
        const publicationYear = this.getAttribute("data-publicationyear");
        const genre = this.getAttribute("data-genre");
        const stock = this.getAttribute("data-stock");
        const image = this.getAttribute("data-image");
        const bookId = this.getAttribute("data-id");
        editBookIdField.value = bookId;

        // Ustawienie wartości w formularzu
        editForm.querySelector("[name='title']").value = title;
        editForm.querySelector("[name='author']").value = author;
        editForm.querySelector("[name='publicationyear']").value = publicationYear;
        editForm.querySelector("[name='genre']").value = genre;
        editForm.querySelector("[name='stock']").value = stock;

        // Ustawienie wartości ukrytego pola dla ścieżki pliku
        hiddenFilePathInput.value = image;

        // Logowanie wartości do konsoli
        console.log("Autor:", author);
        console.log("Tytuł:", title);
        console.log("Rok wydania:", publicationYear);
        console.log("Gatunek:", genre);
        console.log("Liczba egzemplarzy:", stock);
        console.log("Ścieżka do obrazka:", image);

        editModal.style.display = "block";

        // Debugowanie zmian w formularzu edycji
        editForm.addEventListener('input', function(event) {
            const target = event.target;
            if (target.name) {
                console.log(`Zmieniono wartość dla ${target.name}:`, target.value);
            }
        });

        // Debugowanie wyboru nowego pliku
        editForm.querySelector('.file-upload').addEventListener('change', function() {
            const fileInput = this;
            if (fileInput.files && fileInput.files[0]) {
                console.log('Wybrany nowy plik:', fileInput.files[0].name);
            }
        });
    });
});

// Nasłuchiwanie na przycisk zamknięcia modalu
editModal.querySelector(".close-button").addEventListener("click", function() {
    editModal.style.display = "none";
});

document.addEventListener("keydown", function(event) {
    if (event.key === "Escape" && editModal.style.display === "block") {
        editModal.style.display = "none";
    }
});

// Nasłuchiwanie na przycisk wysyłania formularza
editForm.addEventListener('submit', function(event) {
    event.preventDefault();  // Zapobiega domyślnemu działaniu formularza

    const formData = new FormData(editForm);

    fetch('/editBook', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                messageBox.innerHTML = `<p style="color: green">${data.message}</p>`;
                setTimeout(() => {
                    messageBox.innerHTML = '';
                    editModal.style.display = "none";
                }, 1500);
            } else {
                messageBox.innerHTML = `<p style="color: red">${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

