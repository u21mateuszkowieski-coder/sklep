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
  
  $sql_top_categories = "
    SELECT *
    FROM top_kategorie
    LIMIT 4
    ";
  $top_categories = $conn->query($sql_top_categories);
  
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
  
  $sql_new = "
    SELECT *
    FROM product
    ORDER BY data_utworzenia DESC
    LIMIT 8
    ";
  $new_products = $conn->query($sql_new);
  
  $sql_promoted = "
    SELECT p.*, SUM(oi.quantity) AS sprzedano
    FROM product p
    LEFT JOIN order_items oi ON oi.id_product = p.id_product
    GROUP BY p.id_product
    ORDER BY sprzedano DESC
    LIMIT 8
    ";
  $promoted = $conn->query($sql_promoted);

  
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcommerceStore</title>
    <link rel="stylesheet" href="../client-logged-assets/client-styles/client-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body data-logged="true" data-user-id="<?= $_SESSION['user_id'] ?>">
  
    <?php require('../client-components/client-header.php'); ?>
  
    <!-- Hero -->
  <section class="hero" style="background: url('../../assets/images/baner_3.jpeg') center/cover no-repeat;">
    <div class="hero-content">
      <h1>Odkryj produkty, które podbijają rynek</h1>
      <p>Dołącz do tysięcy zadowolonych klientów i znajdź coś dla siebie</p>
      <a href="../client-logged-pages/client-products.php" class="btn-primary">Przeglądaj kolekcję</a>
    </div>
  </section>

    <!-- Katalog kategorii -->
  <section class="section categories">
    <h2>Najpopularniejsze Kategorie</h2>
    <div class="category-grid">
        <?php
        if ($top_categories && $top_categories->num_rows > 0) {
            while ($cat = $top_categories->fetch_assoc()) {
                $img = '/src/assets/images/default-category.jpg';
                $link = "../client-logged-pages/client-products.php?cat=" . $cat['id_category'];
                ?>
                <a href="<?= $link ?>" class="category-card">
                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($cat['nazwa']) ?>">
                    <div class="category-overlay">
                        <span class="category-name"><?= htmlspecialchars($cat['nazwa']) ?></span>
                    </div>
                </a>
                <?php
            }
        } else {
            echo '<p>Brak popularnych kategorii.</p>';
        }
        ?>
    </div>
  </section>
    

    <!-- Promocje -->
  <section class="section promotions">
    <h2>Promocje</h2>
    
    <div class="product-grid">
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

    <!-- Nowości -->
  <section class="section new-products">
    <h2>Nowości w sklepie</h2>
    <div id="new-products" class="product-grid">
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

    <!-- Promowane produkty -->
  <section class="section promoted-products">
    <h2>Promowane produkty</h2>
    <div id="promoted-products" class="product-grid">
      <?php
  if ($promoted && $promoted->num_rows > 0) {
    while ($row = $promoted->fetch_assoc()) {
      
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

    <footer class="site-footer">
        <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
    </footer>

<script src="/src/js/cart.js"></script>
</body>
</html>
