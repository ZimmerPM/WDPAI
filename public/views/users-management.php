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


    <script src="public/js/edit-user.js" defer></script>
    <script src="public/js/remove-user.js" defer></script>

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
        <th>ID</th>
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
                <td><?= $user->getId() ?></td>
                <td><?= $user->getEmail() ?></td>
                <td><?= $user->getName() . ' ' . $user->getLastname() ?></td>
                <td>
                    <?= ($user->getRole() === 'user') ? 'czytelnik' : (($user->getRole() === 'admin') ? 'administrator' : $user->getRole()) ?>
                </td>
                <td>
                    <button
                            class="user-management-buttons"
                            data-action="edit"
                            data-id="<?= $user->getId() ?>"
                            data-email="<?= $user->getEmail() ?>"
                            data-name="<?= $user->getName() ?>"
                            data-lastname="<?= $user->getLastname() ?>"
                            data-role="<?= $user->getRole() ?>"
                    >Edytuj</button>

                    <!-- Sprawdzenie, czy e-mail zalogowanego użytkownika jest taki sam jak e-mail użytkownika w tym wierszu -->
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['email'] != $user->getEmail()): ?>
                        <button
                                class="user-management-buttons"
                                data-action="delete"
                                data-id="<?= $user->getId() ?>"
                        >Usuń</button>
                    <?php else: ?>
                        <button class="user-management-buttons" disabled>Usuń</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Brak użytkowników do wyświetlenia</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<section id="editUserModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Edytuj użytkownika</h2>
        <div class="modal-messageBox"></div>
        <form action="editUser" method="POST">
            <input type="email" id="email" name="email" placeholder="e-mail">
            <input type="text" id="name" name="name" placeholder="imię">
            <input type="text" id="lastname" name="lastname" placeholder="nazwisko">
            <select id="role" name="role" >
                <option value="user">czytelnik</option>
                <option value="admin">administrator</option>
            </select>

            <input type="hidden" id="userId" name="userId">
            <button type="submit">Aktualizuj</button>
        </form>
    </div>
</section>

<!-- Modal potwierdzenia usunięcia użytkownika -->
<section id="deleteUserModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close-button-delete-user">&times;</span>
        <h2>Usuń użytkownika</h2>
        <div class="modal-messageBox"></div>
        <p>Czy na pewno chcesz usunąć tego użytkownika?</p>
        <div class="delete-confirmation">
            <button id="confirmDeleteUser">Tak, usuń</button>
            <button id="cancelDeleteUser">Anuluj</button>
        </div>
    </div>
</section>

</body>
</html>
