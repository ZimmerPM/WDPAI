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
    <script src="public/js/search.js" defer></script>
    <script src="public/js/add-book.js" defer></script>
    <script src="public/js/edit-book.js" defer></script>
    <title>Panel Administratora</title>
</head>

<body class="admin-panel">

<?php
include('header.php');
?>

<div class="admin-head-container">
    <!-- Sekcja z przyciskiem Dodaj pozycję -->
    <div class="admin-button-container">
        <a href="/usersManagement" class="users-management-link">Zarządzanie użytkownikami</a>
        <button id="openAddBookModal" class="add-button">Dodaj pozycję do katalogu</button>
    </div>
    <div class="search-container">
        <input type="text" class="search-input" placeholder="Wyszukaj...">
        <button class="search-button">Szukaj</button>
    </div>
</div>

<!-- Katalog książek - taki sam jak w catalog -->
<div class="header-container">
    <div class="empty-field"></div>
    <table class="catalog-table">
        <thead>
        <tr>
            <th>ID</th>
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
    <?php if (isset($books)): ?>
        <?php foreach ($books as $book): ?>
            <div class="book-entry">
                <div class="book-cover">
                    <img src="<?php echo $book->getImage(); ?>" alt="<?php echo $book->getTitle(); ?>" data-filename="<?php echo basename($book->getImage()); ?>">
                </div>
                <table class="catalog-table">
                    <tbody>
                    <tr>
                        <td><?php echo $book->getId(); ?></td>
                        <td><?php echo $book->getTitle(); ?></td>
                        <td><?php echo $book->getAuthor(); ?></td>
                        <td><?php echo $book->getPublicationYear(); ?></td>
                        <td><?php echo $book->getGenre(); ?></td>
                        <td><?php echo $book->isAvailable() ? 'Dostępna' : 'Niedostępna'; ?></td>
                        <td><?php echo $book->getStock(); ?></td>
                        <?php if (isset($_SESSION['user'])): ?>
                            <td>
                                <div class="btn-container">
                                    <button class="edit-btn"
                                            data-id="<?php echo $book->getId(); ?>"
                                            data-title="<?php echo $book->getTitle(); ?>"
                                            data-author="<?php echo $book->getAuthor(); ?>"
                                            data-publicationyear="<?php echo $book->getPublicationYear(); ?>"
                                            data-genre="<?php echo $book->getGenre(); ?>"
                                            data-stock="<?php echo $book->getStock(); ?>"
                                            data-image="<?php echo $book->getImage(); ?>"
                                    >
                                        Edytuj
                                    </button>

                                    <button>Usuń</button>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Formularz dodawania książek -->
<section id="addBookModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Dodaj pozycję do katalogu</h2>
        <div class="modal-messageBox"></div>
        <form action="addBook" method="POST" enctype="multipart/form-data">
            <?php if (isset($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <?php echo $message; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <input name="author" type="text" placeholder="Autor">
            <input name="title" type="text" placeholder="Tytuł">
            <input name="publicationyear" type="text" placeholder="Rok wydania">
            <input name="genre" type="text" placeholder="Gatunek">
            <input name="stock" type="number" placeholder="Liczba egzemplarzy" min="0">
            <input class="file-upload" name="file" type="file"><br/>
            <button type="submit">Dodaj</button>
        </form>
    </div>
</section>

<!-- Formularz edycji książek -->
<section id="editBookModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Edytuj pozycję w katalogu</h2>
        <div class="modal-messageBox"></div>
        <form action="editBook" method="POST" enctype="multipart/form-data">
            <?php if (isset($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <?php echo $message; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <input type="hidden" name="id" id="editBookId">
            <input name="author" type="text" placeholder="Autor">
            <input name="title" type="text" placeholder="Tytuł">
            <input name="publicationyear" type="text" placeholder="Rok wydania">
            <input name="genre" type="text" placeholder="Gatunek">
            <input name="stock" type="number" placeholder="Liczba egzemplarzy" min="0">
            <input class="file-upload" name="file" type="file"><br/>
            <input type="hidden" id="hiddenFilePath" name="hiddenFilePath" value="">
            <button type="submit">Zaktualizuj</button>
        </form>
    </div>
</section>

</body>
</html>
