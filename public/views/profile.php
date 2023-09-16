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

    <link rel="stylesheet" type="text/css" href="public/css/style.css">
    <link rel="stylesheet" type="text/css" href="public/css/modal-styles.css">
    <link rel="stylesheet" type="text/css" href="public/css/admin-styles.css">

    <script src="public/js/change-password.js" defer></script>

    <title>Mój Profil</title>
</head>
<body class="profile">

<?php
include('header.php');
?>

<div class="profile-container">

    <?php
    $user = $_SESSION['user'];
    $fullName = $user['name'] . ' ' . $user['lastname'];
    $role = ($user['role'] === 'admin') ? 'administrator' : 'czytelnik';

    ?>

    <span class = profile-name> <?php echo $fullName; ?></span>
    <span class = profile-role> <?php echo $role; ?></span>
    <p class="profile-info">
          <span> E-mail: <?php echo $user['email']; ?> </span>
    </p>

    <div class="button-container">
        <button id="openPasswordModal">Zmień hasło</button>
    </div>

    <div id="passwordModal" style="display:none;">
        <div class="modal-content">
            <span class="close-button">×</span>
            <h2>Zmień hasło</h2>
            <div id="messageBox" ></div>
            <form id="changePasswordForm">

                <input type="password" id="currentPassword" name="currentPassword" placeholder="Aktualne hasło" required>
                <span class="error" id="currentPasswordError"></span>

                <input type="password" id="newPassword" name="newPassword" placeholder="Nowe hasło" required>
                <span class="error" id="newPasswordError"></span>

                <input type="password" id="repeatPassword" name="repeatPassword" placeholder="Powtórz nowe hasło" required>
                <span class="error" id="repeatPasswordError"></span>

                <button type="submit">Zatwierdź</button>
            </form>
        </div>
    </div>

</body>
</html>
