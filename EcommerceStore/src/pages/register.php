<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rejestracja – Sklep Internetowy</title>
  <link rel="stylesheet" href="/src/assets/styles/register.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        <a href="/src/pages/favorites.html"><img src="/src/assets/icon/heart.svg" alt="Ulubione"></a>
        <a href="/src/pages/cart.html"><img src="/src/assets/icon/shopping-cart.svg" alt="Koszyk"></a>
        <a href="/src/pages/login.html"><img src="/src/assets/icon/user.svg" alt="Konto"></a>
      </div>
    </div>

    <nav class="main-nav">
      <ul>
        <li><a href="/public/index.html">Strona główna</a></li>
        <li><a href="/src/pages/products.html">Produkty</a></li>
        <li><a href="/src/pages/promoted.html">Promocje</a></li>
        <li><a href="/src/pages/new-products.html">Nowości</a></li>
        <li><a href="/src/pages/contact.html">Kontakt</a></li>
      </ul>
    </nav>
  </header>

  <section class="auth-section">
    <div class="auth-card">
      <h2>Załóż konto</h2>
      <form action="#" method="post">
        <input type="text" name="fullname" placeholder="Imię i nazwisko" required />
        <input type="email" name="email" placeholder="Adres e-mail" required />
        <input type="password" name="password" placeholder="Hasło" required />
        <input type="password" name="confirm-password" placeholder="Powtórz hasło" required />
        <button type="submit">Zarejestruj się</button>
      </form>
      <p>Masz już konto? <a href="login.html">Zaloguj się</a></p>
    </div>
  </section>

  <footer class="site-footer">
    <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
  </footer>

  <script src="/src/assets/js/cart.js"></script>
</body>
</html>
