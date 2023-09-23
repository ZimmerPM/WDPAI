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

    <script src="public/js/cancel-reservation.js" defer></script>
    <script src="public/js/cancel-reservation-admin.js" defer></script>

    <title>Rezerwacje</title>
</head>
<body class="reservations" data-role="<?php echo $_SESSION['user']['role']; ?>">
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
            <th>Autor</th>
            <th>Tytuł Książki</th>
            <th>Data Rezerwacji</th>
            <th>Rezerwacja Do</th>
            <th>Akcje</th>
        </tr>
        </thead>
        <tbody id="reservationsTableBodyAdmin">
        <?php if (isset($reservations)): ?>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo $reservation->getUserId(); ?></td>
                    <td><?php echo $reservation->getUserName(); ?></td>
                    <td><?php echo $reservation->getCopyId(); ?></td>
                    <td><?php echo $reservation->getAuthor(); ?></td> <!-- Dodane pole Autor -->
                    <td><?php echo $reservation->getTitle(); ?></td>
                    <td><?php echo $reservation->getReservationDate(); ?></td>
                    <td><?php echo $reservation->getReservationEnd(); ?></td>
                    <td>
                        <button class="reservations-management-buttons lend-button" data-reservation-id="<?php echo $reservation->getId(); ?>">Wypożycz</button>
                        <button class="reservations-management-buttons cancel-button" data-reservation-id="<?php echo $reservation->getId(); ?>">Anuluj</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <?php
        } elseif($role == 'user') {
            ?>
            <h2>Twoje rezerwacje</h2>
            <table class="reservations-table" id="reservationsTableUser">
                <thead>
                <tr>
                    <th>ID Egzemplarza</th>
                    <th>Autor</th>
                    <th>Tytuł Książki</th>
                    <th>Data Rezerwacji</th>
                    <th>Rezerwacja Do</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody id="reservationsTableBodyUser">
                <?php if (isset($reservations)): ?>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo $reservation->getCopyId(); ?></td>
                            <td><?php echo $reservation->getAuthor(); ?></td>
                            <td><?php echo $reservation->getTitle(); ?></td>
                            <td><?php echo $reservation->getReservationDate(); ?></td>
                            <td><?php echo $reservation->getReservationEnd(); ?></td>
                            <td>
                                <button class='reservations-management-buttons cancel-button' data-reservation-id='<?php echo $reservation->getId(); ?>'>Anuluj</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <?php
        }
    }
    ?>
</section>

<div id="cancelModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-messageBox"></div>
        <p>Czy na pewno chcesz anulować rezerwację książki "<span id="reservationTitle"></span>"?</p>
        <div class="cancel-confirmation">
            <button id="confirmCancel">Tak</button>
            <button id="cancelCancel">Cofnij</button>
        </div>
    </div>
</div>

<div id="cancelModalAdmin" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-messageBox"></div>
        <p>Czy na pewno chcesz anulować rezerwację książki "<span id="adminReservationTitle"></span>" przez użytkownika <span id="userName"></span>?</p>
        <div class="cancel-confirmation">
            <button id="adminConfirmCancel">Tak</button>
            <button id="adminCancelCancel">Cofnij</button>
        </div>
    </div>
</div>


</body>
</html>
