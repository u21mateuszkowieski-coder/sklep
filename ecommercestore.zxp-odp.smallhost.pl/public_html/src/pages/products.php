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
 
    <?php require('../components/header.php'); ?>

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

    <?php require('../components/footer.php'); ?>

    <script src="../js/script.js"></script>
</body>
</html>
