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

  <script>
// Wczytaj koszyk i wyświetl go
let cart = JSON.parse(localStorage.getItem("cart") || "[]");

function renderCart() {
    const itemsDiv = document.querySelector(".cart-items");
    if (cart.length === 0) {
        itemsDiv.innerHTML = `<p style="text-align:center;padding:50px;">Twój koszyk jest pusty.<br><a href="/src/pages/products.php">Wróć do zakupów →</a></p>`;
        document.getElementById("cart-count").textContent = "0";
        document.getElementById("subtotal").textContent = "0,00 zł";
        document.getElementById("total").textContent = "0,00 zł";
        return;
    }

    let totalPrice = 0;
    itemsDiv.innerHTML = "";
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        totalPrice += itemTotal;
        itemsDiv.innerHTML += `
            <div style="display:flex; align-items:center; gap:20px; padding:15px; border-bottom:1px solid #eee;">
                <img src="/src/assets/images/${item.image}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;">
                <div style="flex:1;">
                    <h3>${item.name}</h3>
                    <p>${item.price.toFixed(2)} zł × ${item.quantity}</p>
                </div>
                <div style="font-weight:bold;">${itemTotal.toFixed(2)} zł</div>
            </div>
        `;
    });

    document.getElementById("cart-count").textContent = cart.reduce((s,i)=>s+i.quantity,0);
    document.getElementById("subtotal").textContent = totalPrice.toFixed(2) + " zł";
    const shipping = totalPrice >= 299 ? 0 : 19.99;
    document.getElementById("shipping").textContent = shipping === 0 ? "DARMOWA" : "19,99 zł";
    document.getElementById("total").textContent = (totalPrice + shipping).toFixed(2) + " zł";
}

renderCart();
</script>
</body>
</html>
