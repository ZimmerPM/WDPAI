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
    <link rel="stylesheet" type="text/css" href="public/css/table-styles.css">
    <link rel="stylesheet" type="text/css" href="public/css/admin-styles.css">

    <script src="public/js/search.js" defer></script>

    <title>Katalog książek</title>
</head>

<body class="catalog">

<div class="top-bar">
    <img class="logo" src="public/img/logo.svg" alt="logo">
    <?php if (isset($_SESSION['user'])): ?>
        <span class="user-info"><?php echo $_SESSION['user']['name'] . " " . $_SESSION['user']['lastname']; ?></span>
    <?php else: ?>
        <span class="user-info">Przeglądasz jako Gość</span>
    <?php endif; ?>
</div></div>

<nav>
    <a href="/catalog" class="nav-button active">
        <i class="fa-solid fa-list"></i> <span>Katalog</span>
    </a>

    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="/addBook" class="admin-panel-button">
            <i class="fa-solid fa-cog"></i> <span>Panel administracyjny</span>
        </a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user'])): ?>
        <a href="link2.html" class="nav-button">
            <i class="fa-solid fa-book-open"></i> <span>Wypożyczenia</span>
        </a>

        <a href="link3.html" class="nav-button">
            <i class="fa-regular fa-calendar-check"></i> <span>Rezerwacje</span>
        </a>

        <a href="/profile" class="nav-button">
            <i class="fa-solid fa-user"></i> <span>Moje dane</span>
        </a>
        <a href="/logout" class="nav-button">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Wyloguj</span>
        </a>
    <?php else: ?>
        <a href="/login" class="nav-button">
            <i class="fa-solid fa-user-circle"></i> <span>Logowanie</span>
        </a>
        <a href="/register" class="nav-button">
            <i class="fa-solid fa-user-plus"></i> <span>Rejestracja</span>
        </a>
    <?php endif; ?>
</nav>

<div class="search-container">
    <input type="text" class="search-input" placeholder="Wyszukaj...">
    <button class="search-button">Szukaj</button>
</div>

<div class="header-container">
    <div class="empty-field"></div>
    <table class="catalog-table">
        <thead>
        <tr>
            <th>Tytuł</th>
            <th>Autor</th>
            <th>Rok wydania</th>
            <th>Gatunek</th>
            <th>Dostępność</th>
            <th>Liczba dostępnych egzemplarzy</th>
            <?php if (isset($_SESSION['user'])): ?>
                <th>Operacje</th>
            <?php endif; ?>
        </tr>
        </thead>
    </table>
</div>

<div class="books-container">
    <?php if (isset($books))
    {foreach ($books as $book): ?>
        <div class="book-entry">
            <div class="book-cover">
                <img src="<?php echo $book->getImage(); ?>" alt="<?php echo $book->getTitle(); ?>">
            </div>
            <table class="catalog-table">
                <tbody>
                <tr>
                    <td><?php echo $book->getTitle(); ?></td>
                    <td><?php echo $book->getAuthor(); ?></td>
                    <td><?php echo $book->getPublicationYear(); ?></td>
                    <td><?php echo $book->getGenre(); ?></td>
                    <td><?php echo $book->isAvailable() ? 'Dostępna' : 'Niedostępna'; ?></td>
                    <td><?php echo $book->getStock(); ?></td>
                    <?php if (isset($_SESSION['user'])): ?>
                        <td>
                            <div class="btn-container">
                                <?php if ($book->isAvailable()): ?>
                                    <button>Wypożycz</button>
                                <?php else: ?>
                                    <button disabled>Wypożycz</button>
                                <?php endif; ?>
                                <button class="reserve-btn">Rezerwuj</button>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
                </tbody>
            </table>
        </div>
    <?php endforeach; } ?>
</div>
</body>
</html>