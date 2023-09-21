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
    <link rel="stylesheet" type="text/css" href="public/css/admin-styles.css">

    <script src="public/js/search.js" defer></script>

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
                <th>Operacje</th>
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
                                        <form method="POST" action="borrow">
                                            <input type="hidden" name="bookId" value="<?php echo $book->getId(); ?>">
                                            <button type="submit">Wypożycz</button>
                                        </form>

                                    <?php else: ?>
                                        <button disabled>Wypożycz</button>
                                    <?php endif; ?>

                                    <?php if ($_SESSION['user']['role'] != "admin"): ?>
                                        <button class="reserve-btn">Rezerwuj</button>
                                    <?php else: ?>
                                        <button class="reserve-btn" disabled>Rezerwuj</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach;
    } ?>
</div>
</body>
</html>
