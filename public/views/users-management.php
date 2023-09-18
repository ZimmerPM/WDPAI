<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/faceb1bdbd.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Poppins:wght@100;200;300;400;500&family=Sarabun:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <!-- Preconnects for Performance Improvement -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Twoje style CSS -->
    <link rel="stylesheet" type="text/css" href="public/css/style.css">
    <link rel="stylesheet" type="text/css" href="public/css/table-styles.css">
    <link rel="stylesheet" type="text/css" href="public/css/admin-styles.css">
    <link rel="stylesheet" type="text/css" href="public/css/modal-styles.css">


    <script src="public/js/modal.js" defer></script>

    <title>Zarządzanie użytkownikami</title>
</head>
<body class="user-management">

<?php
include('header.php');
?>

<div class="users-management-container">
    <a href="/adminPanel" class="backwards-link">Wstecz</a>
</div>

<table class="user-table">
    <thead>
    <tr>
        <th>E-mail</th>
        <th>Imię i nazwisko</th>
        <th>Rola</th>
        <th>Operacje</th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($users) && is_array($users)): ?>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?= $user->getEmail() ?></td>
                <td><?= $user->getName() . ' ' . $user->getLastname() ?></td>
                <td><?= $user->getRole() ?></td>
                <td>
                    <button class="user-management-buttons">Edytuj</button>

                    <!-- Sprawdzenie, czy e-mail zalogowanego użytkownika jest taki sam jak e-mail użytkownika w tym wierszu -->
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['email'] != $user->getEmail()): ?>
                        <button class="user-management-buttons">Usuń</button>
                    <?php else: ?>
                        <button class="user-management-buttons" disabled>Usuń</button>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">Brak użytkowników do wyświetlenia</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<section id="editUserModal" class="modal" id="editUserModal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Edytuj użytkownika</h2>
        <form action="path_to_your_endpoint" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="name">Imię:</label>
            <input type="text" id="name" name="name" required>

            <label for="lastname">Nazwisko:</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="role">Rola:</label>
            <select id="role" name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <input type="hidden" id="userId" name="userId">
            <input type="submit" value="Aktualizuj">
        </form>
    </div>
</section>


</body>
</html>
