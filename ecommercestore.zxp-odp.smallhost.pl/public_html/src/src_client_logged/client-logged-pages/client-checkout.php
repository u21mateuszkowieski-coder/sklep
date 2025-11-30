<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /src/pages/login.php");
    exit();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/api/db.php';

$user_id = $_SESSION['user_id'];

// Pobierz dane koszyka z bazy
$stmt = $conn->prepare("
    SELECT k.id_product, k.ilosc, p.nazwa, p.cena, p.zdjecie
    FROM koszyk k
    JOIN product p ON k.id_product = p.id_product
    WHERE k.id_user = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    header("Location: client-cart.php");
    exit();
}

$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['cena'] * $item['ilosc'];
}
$shipping = $subtotal >= 299 ? 0 : 19.99;
$total = $subtotal + $shipping;
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout – finalizacja zamówienia</title>
    <link rel="stylesheet" href="/src/assets/styles/checkout.css">
    <style>
        body { font-family: 'Raleway', sans-serif; background:#f8f8f8; margin:0; padding:0; }
        .container { max-width: 1100px; margin: 40px auto; background:white; padding:30px; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.1); }
        h1 { color:#ff6b35; text-align:center; }
        .summary { background:#f4f4f4; padding:20px; border-radius:8px; margin:20px 0; }
        .item { display:flex; align-items:center; gap:20px; padding:15px 0; border-bottom:1px solid #eee; }
        .item img { width:70px; height:70px; object-fit:cover; border-radius:8px; }
        .total { font-size:1.5em; font-weight:bold; text-align:right; margin:20px 0; }
        button { background:#ff6b35; color:white; padding:15px 30px; font-size:1.2em; border:none; border-radius:8px; cursor:pointer; width:100%; }
        button:hover { background:#e55a2b; }
    </style>
</head>
<body data-logged="true" data-user-id="<?= $_SESSION['user_id'] ?>">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/src/src_client_logged/client-components/client-header.php'; ?>

<div class="container">
    <h1>Finalizacja zamówienia</h1>

    <div class="summary">
        <h2>Twoje zamówienie</h2>
        <?php foreach ($cart_items as $item):
            $item_total = $item['cena'] * $item['ilosc'];
        ?>
            <div class="item">
                <img src="/src/assets/images/<?= $item['zdjecie'] ?: 'default-product.jpg' ?>" alt="<?= htmlspecialchars($item['nazwa']) ?>">
                <div style="flex:1;">
                    <strong><?= htmlspecialchars($item['nazwa']) ?></strong><br>
                    <?= $item['cena'] ?> zł × <?= $item['ilosc'] ?>
                </div>
                <div><?= number_format($item_total, 2) ?> zł</div>
            </div>
        <?php endforeach; ?>

        <div style="margin-top:20px; padding-top:20px; border-top:2px solid #ddd;">
            <div style="display:flex; justify-content:space-between;"><span>Wartość produktów:</span> <strong><?= number_format($subtotal, 2) ?> zł</strong></div>
            <div style="display:flex; justify-content:space-between;"><span>Wysyłka:</span> <strong><?= $shipping == 0 ? 'DARMOWA' : '19,99 zł' ?></strong></div>
            <div class="total">Do zapłaty: <?= number_format($total, 2) ?> zł</div>
        </div>
    </div>

    <form action="process-order.php" method="post">
        <button type="submit">Złóż zamówienie i zapłać</button>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/src/components/footer.php'; ?>
<script src="/src/js/cart.js"></script></body>
</html>
