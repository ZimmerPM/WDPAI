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
    <title>Dodawanie książek do katalogu</title>
</head>

<body class="add-book">

<?php
include('header.php');
?>

<!-- Sekcja z przyciskiem Dodaj pozycję -->
<div class="add-button-container">
    <button onclick="document.querySelector('.book-form').scrollIntoView({ behavior: 'smooth' });">Dodaj pozycję</button>
</div>

<!-- Katalog książek - taki sam jak w catalog -->
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
                                    <button disabled>Edytuj</button>
                                <?php endif; ?>
                                <button class="reserve-btn">Usuń</button>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
                </tbody>
            </table>
        </div>
    <?php endforeach; } ?>
</div>

<!-- Formularz dodawania książek -->
<section class="book-form">
    <h1>DODAWANIE POZYCJI DO KATALOGU</h1>
    <form action="addBook" method="POST" ENCTYPE="multipart/form-data">
        <?php
        if (isset($messages))
        {
            foreach ($messages as $message)
            {
                echo $message;
            }
        }
        ?>
        <input name="author" type="text" placeholder="Autor">
        <input name="title" type="text" placeholder="Tytuł">
        <input name="publicationYear" type="text" placeholder="Rok wydania">
        <input name="genre" type="text" placeholder="Gatunek">
        <input name="stock" type="number" placeholder="Liczba egzemplarzy" min="0">
        <input class="file-upload" name="file" type="file"><br/>
        <button type="submit">Dodaj</button>
    </form>
</section>

</body>
</html>