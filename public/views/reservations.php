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
    <link rel="stylesheet" type="text/css" href="public/css/admin-styles.css">

    <title>Rezerwacje</title>
</head>
<body class="reservations">
<?php include('header.php'); ?>
<section>
    <?php
    if(isset($_SESSION['user'])) {
        $role = $_SESSION['user']['role'];
        if($role == 'admin') {
            ?>
            <h2>Bieżące rezerwacje</h2>
            <table class="reservations-table" id="reservationsTableAdmin">
                <thead>
                <tr>
                    <th>ID Użytkownika</th>
                    <th>Imię i Nazwisko</th>
                    <th>ID Egzemplarza</th>
                    <th>Tytuł Książki</th>
                    <th>Data Rezerwacji</th>
                    <th>Rezerwacja Do</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody id="reservationsTableBodyAdmin">
                <!-- Przykładowe rekordy: -->
                <tr>
                    <td>1</td>
                    <td>Jan Kowalski</td>
                    <td>101</td>
                    <td>Władca Pierścieni</td>
                    <td>2023-09-20</td>
                    <td>2023-09-27</td>
                    <td>
                        <button class="reservations-management-buttons">Anuluj</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Maria Nowak</td>
                    <td>102</td>
                    <td>Harry Potter</td>
                    <td>2023-09-21</td>
                    <td>2023-09-28</td>
                    <td>
                        <button class="reservations-management-buttons">Anuluj</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php
        } elseif($role == 'user') {
            ?>
            <table class="reservations-table" id="reservationsTableUser">
                <thead>
                <tr>
                    <th>ID Egzemplarza</th>
                    <th>Tytuł Książki</th>
                    <th>Data Rezerwacji</th>
                    <th>Rezerwacja Do</th>
                </tr>
                </thead>
                <tbody id="reservationsTableBodyUser">
                <!-- Przykładowe rekordy: -->
                <tr>
                    <td>101</td>
                    <td>Władca Pierścieni</td>
                    <td>2023-09-20</td>
                    <td>2023-09-27</td>
                </tr>
                <tr>
                    <td>102</td>
                    <td>Harry Potter</td>
                    <td>2023-09-21</td>
                    <td>2023-09-28</td>
                </tr>
                </tbody>
            </table>
            <?php
        }
    }
    ?>
</section>
</body>
</html>