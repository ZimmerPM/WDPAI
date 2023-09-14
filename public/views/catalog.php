<?php
require_once __DIR__.'/../../src/models/Book.php'; // Poprawiona ścieżka do Book.php
require_once __DIR__.'/../../src/repository/BookRepository.php'; // Poprawiona ścieżka do BookRepository.php


$bookRepository = new BookRepository();
$books = $bookRepository->getBooks();

?>

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

    <script src="public/js/search.js" defer></script>

    <title>Katalog książek</title>
</head>
<body class="catalog">

<div class="top-bar">
    <img class="logo" src="public/img/logo.svg" alt="logo">
</div>

<nav>
    <a href="link1.html" class="nav-button active">
        <i class="fa-solid fa-list"></i> <span>Katalog</span>
    </a>

    <a href="link2.html" class="nav-button">
        <i class="fa-solid fa-book-open"></i> <span>Wypożyczenia</span>
    </a>

    <a href="link3.html" class="nav-button">
        <i class="fa-regular fa-calendar-check"></i> <span>Rezerwacje</span>
    </a>

    <a href="link4.html" class="nav-button">
        <i class="fa-solid fa-user"></i> <span>Moje dane</span>
    </a>

    <a href="/logout" class="nav-button">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Wyloguj</span>
    </a>
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
            <th>Operacje</th>
        </tr>
        </thead>
    </table>
</div>

<?php foreach ($books as $book): ?>
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
                <td>
                    <?php if ($book->isAvailable()): ?>
                        <button>Wypożycz</button>
                    <?php else: ?>
                        <button disabled>Wypożycz</button>
                    <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php endforeach; ?>
</body>
</html>