<?php
session_start();

if (!isset($_SESSION['logged']) || !isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !empty($_SESSION['is_admin'])) {
    header("Location: /public/index.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/api/db.php';

// === FILTR PO KATEGORII
$where = "";
$params = [];
$types = "";

if (isset($_GET['cat']) && is_numeric($_GET['cat'])) {
    $cat_id = (int)$_GET['cat'];
    $where = "WHERE id_category = ?";
    $params[] = $cat_id;
    $types = "i";
}

// Zapytanie główne
$sql = "SELECT id_product, nazwa, opis, cena, zdjecie FROM product $where ORDER BY id_product DESC";

if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

// Kategorie do sidebaru
$cat_result = $conn->query("SELECT id_category AS id, nazwa FROM category ORDER BY nazwa");
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
<body data-logged="true" data-user-id="<?= $_SESSION['user_id'] ?>">

    <?php require('../client-components/client-header.php'); ?>

    <section class="products-page">
        
        <!-- SIDEBAR Z KATEGORIAMI -->
        <aside class="sidebar">
            <h2>Kategorie</h2>
            <ul>
                <li><a href="client-products.php" <?= !isset($_GET['cat']) ? 'style="font-weight: bold; color: #ffd60a;"' : '' ?>>
                    Wszystkie produkty
                </a></li>
                <?php
                if ($cat_result && $cat_result->num_rows > 0) {
                    while ($cat = $cat_result->fetch_assoc()) {
                        $active = (isset($_GET['cat']) && $_GET['cat'] == $cat['id']) ? 'style="font-weight: bold; color: #ffd60a;"' : '';
                        echo '<li><a href="client-products.php?cat=' . $cat['id'] . '" ' . $active . '>' . htmlspecialchars($cat['nazwa']) . '</a></li>';
                    }
                } else {
                    echo '<li>Brak kategorii</li>';
                }
                ?>
            </ul>
        </aside>

        <div class="products-content">
            <h2>Nasze produkty</h2>
            <div class="product-grid">
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $zdjecie = !empty($row["zdjecie"]) ? $row["zdjecie"] : 'default-product.jpg';

                        echo '<div class="product-card">';
                        echo '<img src="/src/assets/images/' . $zdjecie . '" alt="' . htmlspecialchars($row["nazwa"]) . '">';
                        echo '<h3>' . htmlspecialchars($row["nazwa"]) . '</h3>';
                        echo '<p>' . htmlspecialchars($row["opis"]) . '</p>';
                        echo '<div class="product-bottom">';
                        echo '<span class="price">' . number_format($row["cena"], 2) . ' zł</span>';
                        echo '<button
                                class="btn-add-cart"
                                data-id="' . $row["id_product"] . '"
                                data-name="' . htmlspecialchars($row["nazwa"]) . '"
                                data-price="' . $row["cena"] . '"
                                data-image="' . $zdjecie . '">
                                Dodaj do koszyka
                              </button>';
                        echo '</div></div>';
                    }
                } else {
                    echo "<p>Brak produktów w tej kategorii.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <p>© 2025 EcommerceStore — Twój styl. Twoje zakupy.</p>
    </footer>

    <script src="/src/assets/js/cart.js"></script>
</body>
</html>
