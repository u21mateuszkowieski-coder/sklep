<?php
session_start();

if (!isset($_SESSION['logged']) ||
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['username']) ||
    !empty($_SESSION['is_admin'])) {
    
    header("Location: /public/index.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/api/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/src/src_client_logged/client-components/client-header.php';  
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twój koszyk – EcommerceStore</title>
    <link rel="stylesheet" href="/src/assets/styles/cart.css">
    <style>
        .cart-item { display:flex; align-items:center; gap:20px; padding:20px; border-bottom:1px solid #eee; }
        .cart-item img { width:90px; height:90px; object-fit:cover; border-radius:12px; }
        .cart-item-info { flex:1; }
        .cart-item-info h3 { margin:0 0 8px 0; font-size:1.3em; }
        .qty-controls button { width:36px; height:36px; font-size:1.2em; }
        .remove-item { background:none; border:none; font-size:1.8em; color:#e74c3c; cursor:pointer; }
        .empty-cart { text-align:center; padding:60px; font-size:1.4em; color:#666; }
    </style>
</head>
<body data-logged="true" data-user-id="<?= $_SESSION['user_id'] ?>">

<section class="cart-page">
    <div class="cart-container">
        <h1>Twój koszyk</h1>

        <div class="cart-header">
            <p>Liczba produktów: <strong id="cart-count">0</strong></p>
            <button class="clear-cart" style="background:#e74c3c;color:white;padding:10px 20px;border:none;border-radius:8px;cursor:pointer;">
                Wyczyść koszyk
            </button>
        </div>

        <div class="cart-content">
            <div class="cart-items">
                <!-- Tu wstawi JS -->
            </div>

            <div class="cart-summary">
                <h2>Podsumowanie</h2>
                <div class="summary-row"><span>Wartość produktów:</span><span id="subtotal">0,00 zł</span></div>
                <div class="summary-row"><span>Dostawa:</span><span id="shipping">19,99 zł</span></div>
                <div class="summary-row total"><span>Do zapłaty:</span><span id="total">0,00 zł</span></div>
                <a href="client-checkout.php"><button class="checkout-button" style="width:100%;padding:15px;font-size:1.2em;margin-top:20px;">Przejdź do płatności</button></a>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/src/components/footer.php'; ?>
<script src="/src/js/cart.js"></script>

</body>
</html>
