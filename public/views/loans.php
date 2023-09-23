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
    <title>Wypożyczenia</title>
</head>
<body class="loans">
<?php include('header.php'); ?>
<section>
    <?php
    if(isset($_SESSION['user'])) {
        $role = $_SESSION['user']['role'];
        if($role == 'admin') {
            ?>
            <h2>Wypożyczenia bieżące</h2>
            <table class="loans-table" id="loansTableAdmin">
                <thead>
                <tr>
                    <th>ID Użytkownika</th>
                    <th>Imię i Nazwisko</th>
                    <th>ID Egzemplarza</th>
                    <th>Tytuł Książki</th>
                    <th>Data Wypożyczenia</th>
                    <th>Termin Zwrotu</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody id="loansTableBodyAdmin">
                <tr>
                    <td>1</td>
                    <td>Jan Kowalski</td>
                    <td>101</td>
                    <td>Władca Pierścieni</td>
                    <td>2023-09-20</td>
                    <td>2023-09-27</td>
                    <td>
                        <button class="loans-management-buttons">Zwróć</button>
                        <button class="loans-management-buttons">Anuluj</button>
                    </td>
                </tr>
                <!-- Dodatkowe rekordy -->
                <tr>
                    <td>2</td>
                    <td>Maria Nowak</td>
                    <td>102</td>
                    <td>Hobbit</td>
                    <td>2023-09-18</td>
                    <td>2023-09-25</td>
                    <td>
                        <button class="loans-management-buttons">Zwróć</button>
                        <button class="loans-management-buttons">Anuluj</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Anna Wiśniewska</td>
                    <td>103</td>
                    <td>Dune</td>
                    <td>2023-09-15</td>
                    <td>2023-09-22</td>
                    <td>
                        <button class="loans-management-buttons">Zwróć</button>
                        <button class="loans-management-buttons">Anuluj</button>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Piotr Zieliński</td>
                    <td>104</td>
                    <td>Pan Tadeusz</td>
                    <td>2023-09-12</td>
                    <td>2023-09-19</td>
                    <td>
                        <button class="loans-management-buttons">Zwróć</button>
                        <button class="loans-management-buttons">Anuluj</button>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Katarzyna Dąbrowska</td>
                    <td>105</td>
                    <td>Potop</td>
                    <td>2023-09-10</td>
                    <td>2023-09-17</td>
                    <td>
                        <button class="loans-management-buttons">Zwróć</button>
                        <button class="loans-management-buttons">Anuluj</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php
        } elseif($role == 'user') {
            ?>
            <h2>Wypożyczenia bieżące</h2>
            <table class="loans-table" id="loansTableUser">
                <thead>
                <tr>
                    <th>ID Egzemplarza</th>
                    <th>Tytuł Książki</th>
                    <th>Data Wypożyczenia</th>
                    <th>Termin Zwrotu</th>
                </tr>
                </thead>
                <tbody id="loansTableBodyUser">
                <tr>
                    <td>101</td>
                    <td>Władca Pierścieni</td>
                    <td>2023-09-20</td>
                    <td>2023-09-27</td>
                </tr>
                <!-- Dodatkowe rekordy -->
                <tr>
                    <td>102</td>
                    <td>Hobbit</td>
                    <td>2023-09-18</td>
                    <td>2023-09-25</td>
                </tr>
                <tr>
                    <td>103</td>
                    <td>Dune</td>
                    <td>2023-09-15</td>
                    <td>2023-09-22</td>
                </tr>
                <tr>
                    <td>104</td>
                    <td>Pan Tadeusz</td>
                    <td>2023-09-12</td>
                    <td>2023-09-19</td>
                </tr>
                <tr>
                    <td>105</td>
                    <td>Potop</td>
                    <td>2023-09-10</td>
                    <td>2023-09-17</td>
                </tr>
                </tbody>
            </table>
            <?php
        }
    }
    ?>

    <section>
        <h3>Wypożyczenia archiwalne</h3>
        <?php
        if (isset($_SESSION['user'])) {
            $role = $_SESSION['user']['role'];
            $userId = $_SESSION['user']['id'];

            // Tutaj należy pobrać dane z bazy danych z tabeli archive_loans
            // dla zalogowanego użytkownika lub dla wszystkich użytkowników, jeśli zalogowany to admin
            // Pamiętaj o implementacji tego zapytania w zależności od Twojego połączenia z bazą danych
            ?>
            <table class="loans-table">
                <thead>
                <tr>
                    <?php if ($role === 'admin') echo "<th>ID Użytkownika</th>"; ?>
                    <?php if ($role === 'admin') echo "<th>Imię i Nazwisko</th>"; ?>
                    <th>ID Egzemplarza</th>
                    <th>Tytuł Książki</th>
                    <th>Data Wypożyczenia</th>
                    <th>Data Zwrotu</th>
                </tr>
                </thead>
                <tbody>
                <!-- Dla celów demonstracyjnych dodaję kilka statycznych rekordów -->
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <tr>
                        <?php if ($role === 'admin') echo "<td>$i</td>"; ?>
                        <?php if ($role === 'admin') echo "<td>Jan Kowalski</td>"; ?>
                        <td>10$i</td>
                        <td>Przykładowa Książka $i</td>
                        <td>2023-0$i-20</td>
                        <td>2023-0$i-27</td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </section>
</section>
</body>
</html>
