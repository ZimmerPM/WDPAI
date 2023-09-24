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


    <title>Archiwalne wypożyczenia</title>

</head>

<body class="loans" data-role="<?php echo $_SESSION['user']['role']; ?>">
<?php include('header.php'); ?>

<section>
    <div class="archive-reverse-container">
        <a href="/loans" class="archive-reverse-link">Wstecz</a>
    </div>

    <h2>Wypożyczenia archiwalne</h2>
    <?php
    if (isset($_SESSION['user'])) {
        $role = $_SESSION['user']['role'];
        ?>
        <table class="loans-table" id="loans-archive-table">
            <thead>
            <tr>
                <?php if ($role === 'admin'): ?>
                    <th>ID Użytkownika</th>
                    <th>Imię i Nazwisko</th>
                <?php endif; ?>
                <th>ID Egzemplarza</th>
                <th>Tytuł Książki</th>
                <th>Data Wypożyczenia</th>
                <th>Data Zwrotu</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (isset($archivedLoans) && count($archivedLoans) > 0):
                foreach ($archivedLoans as $loan):
                    ?>
                    <tr>
                        <?php if ($role === 'admin'): ?>
                            <td><?= $loan->getUserId() ?></td>
                            <td><?= $loan->getUserName() ?></td>
                        <?php endif; ?>
                        <td><?= $loan->getCopyId() ?></td>
                        <td><?= $loan->getTitle() ?></td>
                        <td><?= $loan->getBorrowedDate() ?></td>
                        <td><?= $loan->getActualReturnDate() ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $role === 'admin' ? '6' : '4' ?>" class="no-results-message" id="loans-table-message-archive">Tabela archiwalnych wypożyczeń jest pusta</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
    ?>
</section>
