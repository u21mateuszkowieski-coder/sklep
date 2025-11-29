<?php
  require_once '../../api/db.php';
  
  $sql = "SELECT nazwa, opis, cena FROM product";
  $result = $conn->query($sql);
  $sql_2 = "SELECT nazwa FROM category";
  $result_2 = $conn->query($sql_2);
?>
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
 
    <?php require('../client-components/client-header.php'); ?>

    <section class="products-page">
        <!-- Menu -->
      <aside class="sidebar">
        <h2>Kategorie</h2>
        <ul>
          <?php
      if ($result_2 && $result_2->num_rows > 0) {
        while ($row = $result_2->fetch_assoc()) {
          echo '<li><a href="#">' . htmlspecialchars($row["nazwa"]) . '</a></li>';
        }
      } else {
        echo "<li>Brak kategorii</li>";
      }
      ?>
        </ul>
      </aside>

    <!-- Produkty -->
    <div class="products-content">
        <h2>Nasze produkty</h2>

        <div class="product-grid">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                echo '<div class="product-card">';
                
                echo '<img src="/src/assets/images/default-product.jpg" alt="Produkt">';

                echo '<h3>' . htmlspecialchars($row["nazwa"]) . '</h3>';
                echo '<p>' . htmlspecialchars($row["opis"]) . '</p>';

                echo '<div class="product-bottom">';
                echo '<span class="price">' . htmlspecialchars($row["cena"]) . ' zł</span>';
                echo '<button class="btn-add-cart">Dodaj do koszyka</button>';
                echo '</div>';

                echo '</div>';
            }
        } else {
            echo "<p>Brak produktów w bazie.</p>";
        }

        $conn->close();
        ?>
        </div>
    </div>

</section>

    <footer class="site-footer">
        <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
