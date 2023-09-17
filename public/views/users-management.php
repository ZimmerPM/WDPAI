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
                    <button class="user-management-buttons">Usuń</button>
                    <button class="user-management-buttons">Ranga</button>
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


</body>
</html>
