<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel użytkownika</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>

<header class="site-header">
  <div class="header-container">
    <div class="logo">
      <h1>EcommerceStore</h1>
    </div>
    
    <div class="search-bar">
      <input type="text" placeholder="Szukaj produktów...">
      <button><img src="/src/assets/icon/search.svg" alt="Szukaj"></button>
    </div>
    
    <div class="header-icons">
      <a href="/src/pages/favorites.php"><img src="/src/assets/icon/heart.svg" alt="Ulubione"></a>
      <a href="/src/pages/cart.php"><img src="/src/assets/icon/shopping-cart.svg" alt="Koszyk"></a>
      <a href="/src/pages/dashboard.php"><img src="/src/assets/icon/user.svg" alt="Konto"></a>
      <!-- WYLOGUJ SIE -->
    </div>
  </div>
  
  <nav class="main-nav">
    <ul>
      <li><a href="/public/index.php">Strona główna</a></li>
      <li><a href="/src/pages/products.php">Produkty</a></li>
      <li><a href="/src/pages/promoted.php">Promocje</a></li>
      <li><a href="/src/pages/new-products.php">Nowości</a></li>
      <li><a href="/src/pages/contact.php">Kontakt</a></li>
    </ul>
  </nav>
</header>

<section class="user-section">
    <h2>Witaj ponownie</h2>

    <div class="user-grid">
        <a href="orders.php" class="user-card">
            <h3>📦 Twoje zamówienia</h3>
            <p>Sprawdź status i historię zakupów</p>
        </a>

        <a href="favorites.php" class="user-card">
            <h3>❤️ Ulubione produkty</h3>
            <p>Twoje zapisane produkty</p>
        </a>

        <a href="profile.php" class="user-card">
            <h3>👤 Profil</h3>
            <p>Zobacz i edytuj dane konta</p>
        </a>

        <a href="settings.php" class="user-card">
            <h3>⚙️ Ustawienia konta</h3>
            <p>Zarządzaj bezpieczeństwem i preferencjami</p>
        </a>
    </div>
</section>

</body>
</html>
