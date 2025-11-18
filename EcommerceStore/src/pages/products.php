<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkty - EcommerceStore</title>
    <link rel="stylesheet" href="/src/assets/styles/products.css">
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

    <section class="products-page">
        <!-- Menu kategorii po lewej -->
        <aside class="sidebar">
            <h2>Kategorie</h2>
            <ul>
                <li><a href="#" class="active">Wszystkie</a></li>
                <li><a href="#">Moda</a></li>
                <li><a href="#">Dom</a></li>
                <li><a href="#">Elektronika</a></li>
                <li><a href="#">Akcesoria</a></li>
            </ul>
        </aside>

        <!-- Produkty po prawej -->
        <div class="products-content">
            <section class="section products">
                <div id="products" class="product-grid"></div>
            </section>
        </div>
    </section>

    <footer class="site-footer">
        <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
