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