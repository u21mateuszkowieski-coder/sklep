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
  
  <?php require('../components/header.php'); ?>

  <section class="cart-page">
    <div class="cart-container">
      <h1>Twój koszyk</h1>

      <div class="cart-header">
        <p class="cart-count">Liczba produktów w koszyku: <span id="cart-count">0</span></p>
        <button class="clear-cart">Wyczyść koszyk</button>
      </div>

      <div class="cart-content">
        <div class="cart-items">
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
          <a href="../pages/checkout.php"><button class="checkout-button">Przejdź do kasy</button></a>
        </div>
      </div>
    </div>
  </section>

  <?php require('../components/footer.php'); ?>

  <script src="/src/assets/js/cart.js"></script>
</body>
</html>
