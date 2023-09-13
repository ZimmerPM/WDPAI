<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Rejestracja</title>
</head>
<body>
    <div class="register-container">
        <img class="logo" src="public/img/logo-with-slogan.svg" alt="combination mark logo">
        <form action="register" method="POST">
            <div class="messages">
                <?php
                if (isset($messages)) {
                    foreach ($messages as $message) {
                        echo $message;
                    }
                }
                ?>
            </div>
            <input type="text" name="name" placeholder="imię">
            <input type="text" name="lastname" placeholder="nazwisko">
            <input type="text" name="email" placeholder="e-mail">
            <input type="password" name="password" placeholder="hasło">
            <input type="password" name="confirm-password" placeholder="potwierdź hasło">
            <button type="submit">zarejestruj</button>
        </form>
    </div>
</body>
</html>