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

  
  $sql_new = "
    SELECT *
    FROM product
    ORDER BY data_utworzenia DESC
    ";
  $new_products = $conn->query($sql_new);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkty - EcommerceStore</title>
    <link rel="stylesheet" href="/src/assets/styles/promoted.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body data-logged="true" data-user-id="<?= $_SESSION['user_id'] ?>">
  
    <?php require('../client-components/client-header.php'); ?>

<section class="products-page">
        <div class="products-content">
            <section class="section new-products">
                <h1>Nowości</h1>
                <div id="new-products-page" class="product-grid">
                          <?php
        if ($new_products && $new_products->num_rows > 0) {
            while ($row = $new_products->fetch_assoc()) {

                echo '<div class="product-card">';

                $img = !empty($row["zdjecie"])
                    ? htmlspecialchars($row["zdjecie"])
                    : "/src/assets/images/default-product.jpg";

                echo '<img src="' . $img . '" alt="' . htmlspecialchars($row["nazwa"]) . '">';

                echo '<h3>' . htmlspecialchars($row["nazwa"]) . '</h3>';
                echo '<p>' . htmlspecialchars(mb_strimwidth($row["opis"], 0, 90, "...")) . '</p>';

                echo '<div class="product-bottom">';
                echo '<span class="price">' . number_format($row["cena"], 2) . ' zł</span>';
                echo '<button
        class="btn-add-cart"
        data-id="' . $row["id_product"] . '"
        data-name="' . htmlspecialchars($row["nazwa"]) . '"
        data-price="' . $row["cena"] . '"
        data-image="' . ($row["zdjecie"] ?? 'default-product.jpg') . '">
        Dodaj do koszyka
      </button>';
                echo '</div>';

                echo '</div>';
            }
        } else {
            echo "<p>Brak produktów w bazie.</p>";
        }
        ?>
                </div>
            </section>
        </div>
    </section>

    <footer class="site-footer">
        <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
    </footer>

<script src="/src/js/cart.js"></script>
</body>
</html>
