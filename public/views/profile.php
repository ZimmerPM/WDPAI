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

    <script src="public/js/password-change.js" defer></script>


    <title>Mój Profil</title>
</head>
<body class="profile">

<div class="top-bar">
    <img class="logo" src="public/img/logo.svg" alt="logo">
    <span class="user-info"><?php echo $_SESSION['user']['name'] . " " . $_SESSION['user']['lastname']; ?></span>
</div>

<nav>
    <a href="/catalog" class="nav-button">
        <i class="fa-solid fa-list"></i> <span>Katalog</span>
    </a>

    <a href="link2.html" class="nav-button">
        <i class="fa-solid fa-book-open"></i> <span>Wypożyczenia</span>
    </a>

    <a href="link3.html" class="nav-button">
        <i class="fa-regular fa-calendar-check"></i> <span>Rezerwacje</span>
    </a>

    <a href="/profile" class="nav-button active">
        <i class="fa-solid fa-user"></i> <span>Moje dane</span>
    </a>

    <a href="/logout" class="nav-button">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Wyloguj</span>
    </a>
</nav>

<div class="profile-container">

    <?php
    $user = $_SESSION['user'];
    $fullName = $user['name'] . ' ' . $user['lastname'];
    $role = ($user['role'] === 'admin') ? 'administrator' : 'czytelnik';

    ?>

    <span class = profile-name> <?php echo $fullName; ?></span>
    <span class = profile-role> <?php echo $role; ?></span>
    <p class="profile-info">
          <span> E-mail: <?php echo $user['email']; ?> <span>
    </p>

    <div class="button-container">
        <button id="openPasswordModal">Zmień hasło</button>
    </div>

    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closePasswordModal">&times;</span>
            <h2>Zmień hasło</h2>
            <form id="changePasswordForm">
                <div class="form-group">
                    <label for="currentPassword">Aktualne hasło</label>
                    <input type="password" id="currentPassword" name="currentPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">Nowe hasło</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Potwierdź nowe hasło</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit">Zapisz zmiany</button>
            </form>
        </div>
    </div>


</div>

</body>
</html>
