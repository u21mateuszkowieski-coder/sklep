<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Koszyk - EcommerceStore</title>
  <link rel="stylesheet" href="/src/assets/styles/cart.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  
    <?php require('../client-components/client-header.php'); ?>

  <section class="cart-page">
    <div class="cart-container">
      <h1>Twój koszyk</h1>

      <div class="cart-header">
        <p class="cart-count">Liczba produktów w koszyku: <span id="cart-count">0</span></p>
        <button class="clear-cart">Wyczyść koszyk</button>
      </div>

      <div class="cart-content">
        <div class="cart-items">
          <!-- Produkty dodane dynamicznie przez JS -->
          <!-- Musza tutaj sie znajowac  -->
        </div>

        <div class="cart-summary">
          <h2>Podsumowanie</h2>
          <div class="summary-row">
            <span>Wartość produktów:</span>
            <span id="subtotal">0,00 zł</span>
          </div>
          <div class="summary-row">
            <span>Dostawa:</span>
            <span id="shipping">0,00 zł</span>
          </div>
          <div class="summary-row total">
            <span>Łącznie do zapłaty:</span>
            <span id="total">0,00 zł</span>
          </div>
          <a href="../client-logged-pages/client-checkout.php"><button class="checkout-button">Przejdź do kasy</button></a>
        </div>
      </div>

      <div id="empty-cart" class="empty-cart-message hidden">
        <img src="/src/assets/icon/shopping-cart.svg" alt="Pusty koszyk" class="empty-icon">
        <h2>Twój koszyk jest pusty</h2>
        <p>Dodaj produkty do koszyka, aby rozpocząć zakupy</p>
        <a href="/src/pages/products.html" class="btn-primary">Przeglądaj produkty</a>
      </div>
    </div>
  </section>

  <footer class="site-footer">
    <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
  </footer>

  <script src="/src/assets/js/cart.js"></script>
</body>
</html>
