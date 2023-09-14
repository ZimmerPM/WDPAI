<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500&display=swap" rel="stylesheet">

    <!-- Preconnects for Performance Improvement -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script type="text/javascript" src="./public/js/validation.js" defer></script>
    
    <title>Logowanie</title>
</head>

<body>
    <div class="login-container">

        <!-- Logo -->
        <img class="logo" src="public/img/logo-with-slogan.svg" alt="combination mark logo">

        <!-- Login Form -->
        <form action="login" method="POST">
            <div class="messages">
                <?php
                if (isset($messages))
                {
                    foreach ($messages as $message)
                    {
                        echo $message;
                    }
                }
                ?>
            </div>
            <input type="text" name="email" placeholder="e-mail">
            <input type="password" name="password" placeholder="password">
            <button type="submit">zaloguj</button>
            <a href="register" class="register-link">Nie masz konta? Zarejestruj się!</a>
        </form>

    </div>
</body>

</html>