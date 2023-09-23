<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/faceb1bdbd.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Poppins:wght@100;200;300;400;500&family=Sarabun:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" type="text/css" href="public/css/style.css">
    <link rel="stylesheet" type="text/css" href="public/css/table-styles.css">
    <link rel="stylesheet" type="text/css" href="public/css/modal-styles.css">

    <script src="public/js/search.js" defer></script>
    <script src="public/js/borrow-book.js" defer></script>

    <title>Katalog książek</title>

</head>

<body class="catalog">
<?php include('header.php'); ?>

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
                <th>Akcja</th>
            <?php endif; ?>
        </tr>
        </thead>
    </table>
</div>

<div class="books-container">
    <?php if (isset($books)) {
        foreach ($books as $book): ?>
            <div class="book-entry" data-id="<?php echo $book->getId(); ?>">
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
                                <?php if ($book->isAvailable() && $_SESSION['user']['role'] != "admin"): ?>
                                    <button class="borrow-btn" data-book-id="<?php echo $book->getId(); ?>" data-book-title="<?php echo $book->getTitle(); ?>">Wypożycz</button>
                                <?php else: ?>
                                    <button disabled>Wypożycz</button>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; } ?>
</div>


<!-- Modal -->

<div id="reserveModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-messageBox"></div>
        <p>Czy na pewno chcesz zarezerwować egzemplarz książki "<span id="bookTitle"></span>" do wypożyczenia?</p>
        <div class="reserve-confirmation">
            <button id="confirmReserve">Rezerwuj</button>
            <button id="cancelReserve">Anuluj</button>
        </div>
    </div>
</div>
</body>
</html>

