<?php
  require_once '../api/db.php';
  
  $sql_promocje = "
SELECT 
    p.id_product,
    p.nazwa,
    p.opis,
    p.zdjecie,
    pp.cena_promocyjna AS cena,
    p.cena AS cena_normalna,
    ROUND((1 - pp.cena_promocyjna / p.cena) * 100) AS znizka_procent,
    pp.nazwa_promocji,
    pp.data_zakonczenia
FROM product p
INNER JOIN produkty_w_promocji pp ON p.id_product = pp.id_product
WHERE pp.aktywna = 1
  AND (pp.data_zakonczenia IS NULL OR pp.data_zakonczenia >= NOW())
ORDER BY pp.data_rozpoczecia DESC;
    ";
  $promocje = $conn->query($sql_promocje);
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
<body>

    <?php require('../components/header.php'); ?>

    <section class="products-page">
        <div class="products-content">
            <section class="section products">
                <h1>Na Promocji</h1>
                <div id="promoted-product-page" class="product-grid">
              <?php
        if ($promocje && $promocje->num_rows > 0) {
            while ($row = $promocje->fetch_assoc()) {

                echo '<div class="product-card">';

                $img = !empty($row["zdjecie"])
                    ? htmlspecialchars($row["zdjecie"])
                    : "/src/assets/images/default-product.jpg";

                echo '<img src="' . $img . '" alt="' . htmlspecialchars($row["nazwa"]) . '">';

                echo '<h3>' . htmlspecialchars($row["nazwa"]) . '</h3>';
                echo '<p>' . htmlspecialchars(mb_strimwidth($row["opis"], 0, 90, "...")) . '</p>';

                echo '<div class="product-bottom">';
                echo '<span class="price">' . number_format($row["cena"], 2) . ' zł</span>';
                echo '<button class="btn-add-cart">Dodaj do koszyka</button>';
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

    <?php require('../components/footer.php'); ?>

    <script src="../js/script.js"></script>
</body>
</html>
