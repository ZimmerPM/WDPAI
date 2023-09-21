document.addEventListener("DOMContentLoaded", function() {
    const editModal = document.getElementById("editUserModal");
    const editForm = editModal.querySelector("form");
    const userIdField = document.getElementById('userId');
    const messageBox = editModal.querySelector(".modal-messageBox");
    const submitButton = editForm.querySelector('button[type="submit"]');
    const closeButton = editModal.querySelector(".close-button");

    function clearForm(form) {
        for (let element of form.elements) {
            switch (element.type) {
                case 'text':
                case 'email':
                case 'select-one':
                    element.value = '';
                    break;
            }
        }
    }

    closeButton.addEventListener("click", function() {
        editModal.style.display = "none";
        clearForm(editForm);
    });

    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && editModal.style.display === "block") {
            closeButton.click();
        }
    });

    document.querySelectorAll(".user-management-buttons").forEach(button => {
        button.addEventListener("click", function() {
            if (button.innerText === "Edytuj") {
                const email = button.getAttribute("data-email");
                const name = button.getAttribute("data-name");
                const lastname = button.getAttribute("data-lastname");
                const role = button.getAttribute("data-role");
                const userId = button.getAttribute("data-id");

                userIdField.value = userId;
                editForm.querySelector("[name='email']").value = email;
                editForm.querySelector("[name='name']").value = name;
                editForm.querySelector("[name='lastname']").value = lastname;
                editForm.querySelector("[name='role']").value = role;

                editModal.style.display = "block";
                messageBox.innerText = "";
                submitButton.disabled = false;
                submitButton.innerText = "Aktualizuj";
            }
        });
    });

    editForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(editForm);

        fetch('/editUser', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageBox.innerHTML = `<p style="color: green">${data.message}</p>`;
                    submitButton.disabled = true;
                    submitButton.innerText = "Wykonano";

                    const userId = userIdField.value;
                    const editedEntry = document.querySelector(`.user-management-buttons[data-id="${userId}"]`).closest('tr');
                    editedEntry.querySelector(`td:nth-child(2)`).textContent = formData.get("email");
                    editedEntry.querySelector(`td:nth-child(3)`).textContent = formData.get("name") + ' ' + formData.get("lastname");
                    editedEntry.querySelector(`td:nth-child(4)`).textContent = formData.get("role") === "user" ? "czytelnik" : "administrator";

                    editModal.addEventListener("click", function(event) {
                        if (event.target === editModal) {
                            closeButton.click();
                        }
                    });
                } else {
                    messageBox.innerHTML = `<p style="color: red">${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});