<div class="top-bar">
    <img class="logo" src="public/img/logo.svg" alt="logo">
    <?php if (isset($_SESSION['user'])): ?>
        <span class="user-info"><?php echo $_SESSION['user']['name'] . " " . $_SESSION['user']['lastname']; ?></span>
    <?php else: ?>
        <span class="user-info">Przeglądasz jako Gość</span>
    <?php endif; ?>
</div>

<nav>
    <a href="/catalog" class="nav-button <?php echo ($_SERVER['REQUEST_URI'] == '/catalog') ? 'active' : ''; ?>">
        <i class="fa-solid fa-list"></i> <span>Katalog</span>
    </a>

    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="/addBook" class="admin-panel-button <?php echo ($_SERVER['REQUEST_URI'] == '/addBook') ? 'active' : ''; ?>">
            <i class="fa-solid fa-cog"></i> <span>Panel administracyjny</span>
        </a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user'])): ?>
        <a href="link2.html" class="nav-button <?php echo ($_SERVER['REQUEST_URI'] == 'link2.html') ? 'active' : ''; ?>">
            <i class="fa-solid fa-book-open"></i> <span>Wypożyczenia</span>
        </a>

        <a href="link3.html" class="nav-button <?php echo ($_SERVER['REQUEST_URI'] == 'link3.html') ? 'active' : ''; ?>">
            <i class="fa-regular fa-calendar-check"></i> <span>Rezerwacje</span>
        </a>

        <a href="/profile" class="nav-button <?php echo ($_SERVER['REQUEST_URI'] == '/profile') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user"></i> <span>Moje dane</span>
        </a>
        <a href="/logout" class="nav-button">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Wyloguj</span>
        </a>
    <?php else: ?>
        <a href="/login" class="nav-button <?php echo ($_SERVER['REQUEST_URI'] == '/login') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-circle"></i> <span>Logowanie</span>
        </a>
        <a href="/register" class="nav-button <?php echo ($_SERVER['REQUEST_URI'] == '/register') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-plus"></i> <span>Rejestracja</span>
        </a>
    <?php endif; ?>
</nav>