<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcommerceStore</title>
    <link rel="stylesheet" href="../client-logged-assets/client-styles/client-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  
    <?php require('../client-components/client-header.php'); ?>
  
    <!-- Hero / baner -->
    <section class="hero">
        <div class="hero-content">
        <h1>Nowa kolekcja 2025</h1>
        <p>Styl, elegancja i wygoda — odkryj nowy wymiar zakupów online</p>
        <a href="../src/pages/products.php" class="btn-primary">Zobacz kolekcję</a>
        </div>
    </section>

    <!-- Katalog kategorii -->
    <section class="section categories">
        <h2>Najpopularniejsze Kategorie</h2>
        <div class="category-grid">
        <div class="category-card">
            <img src="../src/assets/images/tlo.jpg" alt="Moda">
            <span>Moda</span>
        </div>
        <div class="category-card">
            <img src="../src/assets/images/tlo.jpg" alt="Dom">
            <span>Dom</span>
        </div>
        <div class="category-card">
            <img src="../src/assets/images/tlo.jpg" alt="Elektronika">
            <span>Elektronika</span>
        </div>
        <div class="category-card">
            <img src="../src/assets/images/tlo.jpg" alt="Akcesoria">
            <span>Akcesoria</span>
        </div>
        </div>
    </section>
    

    <!-- Promocje -->
    <section class="section promotions">
        <h2>Promocje</h2>
        <div id="promotions" class="product-grid"></div>
    </section>

    <!-- Nowości -->
     <section class=" section new products">
        <h2>Nowości w sklepie</h2>
        <div id="new-products" class="product-grid"></div>
     </section>

    <!-- Promowane produkty -->
    <section class="section promoted-products">
        <h2>Promowane produkty</h2>
        <div id="promoted-products" class="product-grid"></div>
    </section>

    <footer class="site-footer">
        <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
    </footer>

    <script src="../src/js/script.js"></script>
</body>
</html>
