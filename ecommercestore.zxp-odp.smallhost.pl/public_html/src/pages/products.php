<?php
require_once '../api/db.php';

$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// === Produkty ===
$sql_products = "SELECT p.id_product, p.nazwa, p.opis, p.cena, p.zdjecie, c.nazwa AS nazwa_kategorii
                 FROM product p
                 LEFT JOIN category c ON p.id_category = c.id_category";
if ($selected_category > 0) {
    $sql_products .= " WHERE p.id_category = ?";
}
$sql_products .= " ORDER BY p.data_utworzenia DESC";

$stmt = $conn->prepare($sql_products);
if ($selected_category > 0) {
    $stmt->bind_param("i", $selected_category);
}
$stmt->execute();
$result_products = $stmt->get_result();

// wyszystkie produkty do tablicy
$products = [];
while ($row = $result_products->fetch_assoc()) {
    $products[] = $row;
}
$products_count = count($products);

// === Kategorie paska bocznego ===
$sql_categories = "SELECT id_category, nazwa FROM category ORDER BY nazwa";
$result_categories = $conn->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $selected_category > 0 && !empty($products) ? htmlspecialchars($products[0]['nazwa_kategorii']) : 'Wszystkie produkty' ?> - EcommerceStore
    </title>
    <link rel="stylesheet" href="/src/assets/styles/products.css">
    <style>
        .sidebar ul li a.active { background: #ff6b35; color: white; font-weight: 600; border-radius: 8px; padding: 8px 12px; }
        .sidebar ul li a:hover:not(.active) { background: #f0f0f0; }
    </style>
</head>
<body>

<?php require('../components/header.php'); ?>

<section class="products-page">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Kategorie</h2>
        <ul>
            <li><a href="products.php" class="<?= $selected_category == 0 ? 'active' : '' ?>">Wszystkie produkty</a></li>
            <?php while ($cat = $result_categories->fetch_assoc()): ?>
                <li><a href="products.php?category=<?= $cat['id_category'] ?>"
                       class="<?= $selected_category == $cat['id_category'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['nazwa']) ?>
                </a></li>
            <?php endwhile; ?>
        </ul>
    </aside>

    <!-- Produkty -->
    <div class="products-content">
        <h2>
            <?= $selected_category > 0 && !empty($products)
                ? htmlspecialchars($products[0]['nazwa_kategorii'])
                : 'Wszystkie produkty' ?>
            (<?= $products_count ?>)
        </h2>

        <?php if ($products_count > 0): ?>
            <div class="product-grid">
                <?php foreach ($products as $row): ?>
                    <div class="product-card">
                        <?php
                        $img = !empty($row["zdjecie"])
                            ? '/src/assets/images/' . htmlspecialchars($row["zdjecie"])
                            : "/src/assets/images/default-product.jpg";
                        ?>
                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($row["nazwa"]) ?>">
                        <h3><?= htmlspecialchars($row["nazwa"]) ?></h3>
                        <p><?= htmlspecialchars(mb_strimwidth($row["opis"], 0, 90, "...")) ?></p>
                        <div class="product-bottom">
                            <span class="price"><?= number_format($row["cena"], 2) ?> zł</span>
                            <button class="btn-add-cart"
                              data-id="<?= $row["id_product"] ?>"
                              data-name="<?= htmlspecialchars($row["nazwa"]) ?>"
                              data-price="<?= $row["cena"] ?>"
                              data-image="<?= $row["zdjecie"] ?? 'default-product.jpg' ?>"
                              >
                              Dodaj do koszyka
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align:center; padding:40px; font-size:1.2em;">
                Brak produktów w tej kategorii.
            </p>
        <?php endif; ?>
    </div>
</section>

<?php require('../components/footer.php'); ?>

<script>
// Najlepsza wersja koszyka – działa zawsze i wszędzie
let cart = JSON.parse(localStorage.getItem("cart") || "[]");

function updateBadge() {
    const badge = document.getElementById("cart-badge");
    if (badge) {
        const total = cart.reduce((sum, item) => sum + item.quantity, 0);
        badge.textContent = total;
        badge.style.display = total > 0 ? "inline-block" : "none";
    }
}

document.addEventListener("click", function(e) {
    if (e.target && e.target.classList.contains("btn-add-cart")) {
        e.preventDefault();
        
        const btn = e.target;
        const id = btn.dataset.id;
        const name = btn.dataset.name || "Produkt";
        const price = parseFloat(btn.dataset.price) || 0;
        const image = btn.dataset.image || "default-product.jpg";

        if (!id) return;

        const existing = cart.find(x => x.id == id);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({id, name, price, quantity: 1, image});
        }

        localStorage.setItem("cart", JSON.stringify(cart));
        alert("Dodano do koszyka: " + name);
        updateBadge();
    }
});

// Uruchom przy załadowaniu strony
updateBadge();
</script>

<!-- Styl dla badge'a w headerze (jeśli jeszcze nie masz) -->
<style>
#cart-badge {
    background: #ff4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    text-align: center;
    line-height: 20px;
    display: inline-block;
    margin-left: 5px;
}
</style>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
