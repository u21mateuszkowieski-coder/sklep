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
  
  $sql_promocje = "
    SELECT
        p.id_product,
        p.nazwa,
        p.opis,
        p.zdjecie,
        pp.cena_promocyjna AS cena,
        p.cena AS cena_normalna,
        ROUND((1 - pp.cena_promocyjna / p.cena) * 100) AS znizka_procent,
        pp.nazwa_promocji
    FROM product p
    INNER JOIN produkty_w_promocji pp ON p.id_product = pp.id_product
    WHERE pp.aktywna = 1
      AND (pp.data_zakonczenia IS NULL OR pp.data_zakonczenia >= NOW())
    ORDER BY pp.data_rozpoczecia DESC
    LIMIT 8
    ";
  $promocje = $conn->query($sql_promocje);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkty - EcommerceStore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/src/assets/styles/promoted.css">
</head>
<body data-logged="true" data-user-id="<?= $_SESSION['user_id'] ?>">
  
    <?php require('../client-components/client-header.php'); ?>

<section class="products-page">
        <div class="products-content">
            <section class="section products">
                <h1>Na Promocji</h1>
                <div id="promoted-product-page" class="product-grid">
    <?php
    if ($promocje && $promocje->num_rows > 0) {
        while ($row = $promocje->fetch_assoc()) {

            $zdjecie = !empty($row["zdjecie"]) ? $row["zdjecie"] : 'default-product.jpg';
            $cena_normalna = number_format($row["cena_normalna"], 2);
            $cena_promocyjna = number_format($row["cena"], 2);
            $znizka = isset($row["znizka_procent"]) ? $row["znizka_procent"] : round((1 - $row["cena"] / $row["cena_normalna"]) * 100);

            echo '<div class="product-card">';

            echo '<img src="/src/assets/images/' . $zdjecie . '" alt="' . htmlspecialchars($row["nazwa"]) . '">';

            echo '<div class="promo-badge">-' . $znizka . '%</div>';

            echo '<h3>' . htmlspecialchars($row["nazwa"]) . '</h3>';
            echo '<p>' . htmlspecialchars(mb_strimwidth($row["opis"], 0, 90, "...")) . '</p>';

            echo '<div class="product-bottom">';

            echo '<div class="price-container">';
            echo '<span class="price-new"> <b style="font-size: 28px">' . $cena_promocyjna . 'zł</b></span>';
            echo '<span class="price-old"><p style="color: red; text-decoration: line-through; font-size: 20px">' . $cena_normalna . ' zł</p></span>';
            echo '</div>';

            echo '<button
                    class="btn-add-cart"
                    data-id="' . $row["id_product"] . '"
                    data-name="' . htmlspecialchars($row["nazwa"]) . '"
                    data-price="' . $row["cena"] . '"
                    data-image="' . $zdjecie . '">
                    Dodaj do koszyka
                  </button>';

            echo '</div>'; 
            echo '</div>'; 
        }
    } else {
        echo "<p>Brak produktów w promocji.</p>";
    }
    ?>
</div>
            </section>
        </div>
    </section>

    <footer class="site-footer">
        <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
    </footer>

<script src="/src/js/cart.js"></script></body>
</html>
