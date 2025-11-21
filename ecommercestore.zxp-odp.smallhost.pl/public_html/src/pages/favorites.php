<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulubione - EcommerceStore</title>
    <link rel="stylesheet" href="/src/assets/styles/favorites.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php require('../components/header.php'); ?>

    <section class="favorites-page">
        <div class="favorites-container">
            <h1>Ulubione produkty</h1>
            
            <div class="favorites-header">
                <p class="favorites-count">Liczba ulubionych produktów: <span id="favorites-count">0</span></p>
                <button class="clear-favorites">Wyczyść ulubione</button>
            </div>

            <div id="favorites-grid" class="product-grid">
                <!-- Produkty będą dodawane dynamicznie przez JavaScript -->
            </div>

            <div id="no-favorites" class="no-favorites-message">
                <img src="/src/assets/icon/heart.svg" alt="Puste ulubione" class="empty-icon">
                <h2>Nie masz jeszcze ulubionych produktów</h2>
                <p>Dodaj produkty do ulubionych, klikając ikonę serca na karcie produktu</p>
                <a href="/src/pages/products.html" class="btn-primary">Przeglądaj produkty</a>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>