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

    <script src="public/js/cancel-loan.js" defer></script>
    <script src="public/js/return-book.js" defer></script>

    <title>Wypożyczenia</title>

</head>

<body class="loans" data-role="<?php echo $_SESSION['user']['role']; ?>">
<?php include('header.php'); ?>

<div class="archive-button-container">
    <a href="/loansArchive" class="loans-archive-link">Archiwum</a>
</div>

<section>
    <?php
    if (isset($_SESSION['user'])) {
        $role = $_SESSION['user']['role'];
        if ($role === 'admin') {
            ?>
            <h2>Wypożyczenia bieżące</h2>
            <table class="loans-table" id="loansTableAdmin">
                <thead>
                <tr>
                    <th>ID Użytkownika</th>
                    <th>Imię i Nazwisko</th>
                    <th>ID Egzemplarza</th>
                    <th>Autor</th>
                    <th>Tytuł Książki</th>
                    <th>Data Wypożyczenia</th>
                    <th>Termin Zwrotu</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody id="loansTableBodyAdmin">
                <?php if (isset($loans) && count($loans) > 0): ?>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td><?= $loan->getUserId() ?></td>
                            <td><?= $loan->getUserName() ?></td>
                            <td><?= $loan->getCopyId() ?></td>
                            <td><?= $loan->getAuthor() ?></td>
                            <td><?= $loan->getTitle() ?></td>
                            <td><?= $loan->getBorrowedDate() ?></td>
                            <td><?= $loan->getExpectedReturnDate() ?></td>
                            <td>
                                <button class="loans-management-buttons return-button" data-loan-id="<?php echo $loan->getId(); ?>">Zwróć</button>
                                <button class="loans-management-buttons cancel-button" data-loan-id="<?php echo $loan->getId(); ?>">Anuluj</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="no-results-message" id="loans-table-message-admin">Tabela wypożyczeń jest pusta</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <?php
        } elseif ($role === 'user') {
            ?>
            <h2>Wypożyczenia bieżące</h2>
            <table class="loans-table" id="loansTableUser">
                <thead>
                <tr>
                    <th>ID Egzemplarza</th>
                    <th>Autor</th>
                    <th>Tytuł Książki</th>
                    <th>Data Wypożyczenia</th>
                    <th>Termin Zwrotu</th>
                </tr>
                </thead>
                <tbody id="loansTableBodyUser">
                <?php if (isset($loans) && count($loans) > 0): ?>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td><?= $loan->getCopyId() ?></td>
                            <td><?= $loan->getAuthor() ?></td>
                            <td><?= $loan->getTitle() ?></td>
                            <td><?= $loan->getBorrowedDate() ?></td>
                            <td><?= $loan->getExpectedReturnDate() ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-results-message" id="loans-table-message-user">Tabela wypożyczeń jest pusta</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <?php
        }
    }
    ?>
</section>

<!-- Modal dla zwrotu książki -->
<div id="returnModalAdmin" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-messageBox"></div>
        <p>Czy na pewno chcesz zatwierdzić zwrot książki "<span id="returnBookTitle"></span>" przez użytkownika <span id="returnUserName"></span>?</p>
        <div class="return-confirmation">
            <button id="adminConfirmReturn">Tak</button>
            <button id="adminCancelReturn">Cofnij</button>
        </div>
    </div>
</div>

<!-- Modal dla anulowania wypożyczenia -->
<div id="cancelLoanModalAdmin" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-messageBox"></div>
        <p>Czy na pewno chcesz anulować wypożyczenie książki "<span id="cancelLoanBookTitle"></span>" przez użytkownika <span id="cancelLoanUserName"></span>?</p>
        <div class="cancel-loan-confirmation">
            <button id="adminConfirmCancelLoan">Tak</button>
            <button id="adminCancelCancelLoan">Cofnij</button>
        </div>
    </div>
</div>

</body>
</html>
