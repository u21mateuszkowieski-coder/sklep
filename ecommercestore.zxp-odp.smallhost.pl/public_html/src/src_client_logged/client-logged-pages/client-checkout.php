<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Podsumowanie zamówienia – Sklep Internetowy</title>
  <link rel="stylesheet" href="/src/assets/styles/checkout.css" />
</head>
<body>
  
  <?php require('../client-components/client-header.php'); ?>
  
  <!-- CHECKOUT SECTION -->
  <section class="checkout-section">
    <div class="checkout-container">

      <!-- PODSUMOWANIE KOSZYKA -->
      <div class="cart-summary">
        <h2>Twoje zamówienie</h2>
        <div class="cart-item">
          <img src="/src/assets/images/product-sample.jpg" alt="Produkt">
          <div>
            <h3>Smartwatch Pro X</h3>
            <p>Ilość: 1</p>
          </div>
          <span class="price">599,00 zł</span>
        </div>
        <div class="cart-item">
          <img src="/src/assets/images/product-sample2.jpg" alt="Produkt">
          <div>
            <h3>Słuchawki AirSound</h3>
            <p>Ilość: 1</p>
          </div>
          <span class="price">299,00 zł</span>
        </div>

        <div class="cart-total">
          <p>Łączna kwota: <span>898,00 zł</span></p>
        </div>
      </div>

      <!-- FORMULARZ DANYCH -->
      <div class="checkout-form">
        <h2>Dane do wysyłki</h2>
        <form action="#" method="post">
          <div class="form-group">
            <input type="text" name="fullname" placeholder="Imię i nazwisko" required>
            <input type="email" name="email" placeholder="Adres e-mail" required>
          </div>

          <div class="form-group">
            <input type="text" name="address" placeholder="Ulica i numer domu" required>
            <input type="text" name="city" placeholder="Miasto" required>
          </div>

          <div class="form-group">
            <input type="text" name="postal" placeholder="Kod pocztowy" required>
            <input type="tel" name="phone" placeholder="Telefon kontaktowy" required>
          </div>

          <h3>Metoda płatności</h3>
          <div class="payment-methods">
            <label><input type="radio" name="payment" value="card" checked> Karta płatnicza</label>
            <label><input type="radio" name="payment" value="blik"> BLIK</label>
            <label><input type="radio" name="payment" value="transfer"> Przelew bankowy</label>
            <label><input type="radio" name="payment" value="cod"> Płatność przy odbiorze</label>
          </div>

          <div class="delivery-note">
            <textarea name="note" rows="3" placeholder="Uwagi do zamówienia (opcjonalnie)"></textarea>
          </div>

          <button type="submit" class="btn-buy">Kup teraz</button>
        </form>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="site-footer">
    <p>© 2025 EcommerceStore — Dziękujemy za zakupy!</p>
  </footer>

  <script src="/src/assets/js/cart.js"></script>
</body>
</html>
