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

    <div class="book-entry">
        <div class="book-cover">
            <img src="public/uploads/<?=$book->getImage() ?>" >
        </div>
        <table class="catalog-table">
            <tbody>
                <tr>
                    <td><?= $book->getTitle() ?></td>
                    <td><?= $book->getAuthor() ?></td>
                    <td><?= $book->getPublicationYear() ?></td>
                    <td><?= $book->getGenre() ?></td>
                    <td><?= $book->getAvailability() ? 'Dostępna' : 'Niedostępna' ?></td>
                    <td><?= $book->getStock() ?></td>
                    <td><button>Wypożycz</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>