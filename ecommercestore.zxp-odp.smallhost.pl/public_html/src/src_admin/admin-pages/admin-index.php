<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../../public/index.php");
    exit();
}
require '../../api/db.php';

  $conn->query("
    UPDATE produkty_w_promocji 
    SET aktywna = 0 
    WHERE aktywna = 1 
      AND data_zakonczenia IS NOT NULL 
      AND data_zakonczenia < NOW()
");
// === STATYSTYKI ===
$total_products = $total_promotions = $total_users = 0;
try {
    $result = $conn->query("SELECT COUNT(*) FROM product");
    $total_products = $result->fetch_row()[0];

    $result = $conn->query("SELECT COUNT(*) FROM produkty_w_promocji WHERE aktywna = 1 AND (data_zakonczenia >= NOW() OR data_zakonczenia IS NULL)");
    $total_promotions = $result->fetch_row()[0];

    $result = $conn->query("SELECT COUNT(*) FROM users WHERE czy_admin = 0");
    $total_users = $result->fetch_row()[0];
} catch (Exception $e) {}

// Formatowanie liczb
function formatLiczby($liczba) {
    return $liczba > 999 ? number_format($liczba, 0, '', ' ') : $liczba;
}

// Obsługa formularzy i usuwanie
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $image = 'default.jpg';
        if (!empty($_FILES['image']['name'])) {
            $target = "../../public/images/" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            $image = $_FILES['image']['name'];
        }
        $stmt = $conn->prepare("INSERT INTO product (nazwa, opis, cena, id_category, zdjecie) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $name, $description, $price, $category, $image);
        $stmt->execute();
        $message = "Produkt dodany!";
    }

    if (isset($_POST['add_category'])) {
        $nazwa = $_POST['nazwa'];
        $stmt = $conn->prepare("INSERT INTO category (nazwa) VALUES (?)");
        $stmt->bind_param("s", $nazwa);
        $stmt->execute();
        $message = "Kategoria dodana!";
    }

    if (isset($_POST['add_promotion'])) {
        $product = $_POST['product'];
        $discount = $_POST['discount'];
        $end_date = $_POST['end_date'] ?: null;
        $normal = $conn->query("SELECT cena FROM product WHERE id_product = $product")->fetch_row()[0];
        $promo = $normal * (1 - $discount/100);
        $stmt = $conn->prepare("INSERT INTO produkty_w_promocji (id_product, cena_promocyjna, cena_normalna, data_rozpoczecia, data_zakonczenia) VALUES (?, ?, ?, NOW(), ?)");
        $stmt->bind_param("idds", $product, $promo, $normal, $end_date);
        $stmt->execute();
        $message = "Promocja dodana!";
    }
}

if (isset($_GET['delete_product'])) {
    $id = (int)$_GET['delete_product'];
    $conn->query("DELETE FROM product WHERE id_product = $id");
    $message = "Produkt usunięty";
}
if (isset($_GET['delete_category'])) {
    $id = (int)$_GET['delete_category'];
    $conn->query("DELETE FROM category WHERE id_category = $id");
}
if (isset($_GET['delete_promo'])) {
    $id = (int)$_GET['delete_promo'];
    $conn->query("DELETE FROM produkty_w_promocji WHERE id = $id");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admina — Sklep</title>
    <link rel="stylesheet" href="/src/src_admin/admin-assets/admin-styles/admin-style.css">
    <style>
        .grid3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; margin-top: 30px; }
        .info-box { background: #1a2332; padding: 28px 20px; border-radius: 16px; text-align: center; box-shadow: 0 6px 20px rgba(0,0,0,0.3); transition: transform 0.3s ease; }
        .info-box:hover { transform: translateY(-8px); }
        .info-box p { margin: 0 0 10px; color: #a0aec0; font-size: 1rem; }
        .info-box h3 { margin: 0; font-size: 2.8rem; color: #ffd60a; font-weight: 700; }
        .message { background: #28a745; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; text-align: center; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 14px 12px; text-align: left; border-bottom: 1px solid #333; }
        th {
            background: #ffd60a !important;
            color: #000 !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        tr:hover td { background: rgba(255, 214, 10, 0.1); }

        .btn-small { background: #ffd60a; color: black; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; }
        .btn-del { background: #dc3545; color: white; }
    </style>
</head>
<body>

<header class="site-header">
    <div class="header-container">
        <div class="logo"><h1>Panel Admina</h1></div>
        <div class="header-actions">
            <a href="../../../public/index.php"><button class="logout-btn">Wyloguj się</button></a>
        </div>
    </div>
</header>

<div class="admin-wrap">
    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="#" class="menu-link active" data-section="dashboard">Dashboard</a></li>
                <li><a href="#" class="menu-link" data-section="products">Produkty</a></li>
                <li><a href="#" class="menu-link" data-section="add-product">Dodaj produkt</a></li>
                <li><a href="#" class="menu-link" data-section="categories">Kategorie</a></li>
                <li><a href="#" class="menu-link" data-section="promotions">Promocje</a></li>
                <li><a href="#" class="menu-link" data-section="customers">Klienci</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main">
        <?php if ($message) echo "<div class='message'>$message</div>"; ?>

        <!-- DASHBOARD -->
        <section id="dashboard" class="card visible">
            <h2>Podsumowanie sklepu</h2>
            <div class="muted">Najważniejsze dane na <?= date('d.m.Y H:i') ?></div>
            <div class="grid3">
                <div class="info-box"><p>Produkty w ofercie</p><h3 class="counter"><?= formatLiczby($total_products) ?></h3></div>
                <div class="info-box"><p>Aktywne promocje</p><h3 class="counter"><?= formatLiczby($total_promotions) ?></h3></div>
                <div class="info-box"><p>Zarejestrowani klienci</p><h3 class="counter"><?= formatLiczby($total_users) ?></h3></div>
            </div>
        </section>

        <!-- PRODUKTY -->
        <section id="products" class="card hidden">
            <h2>Produkty</h2>
            <table>
                <thead><tr><th>ID</th><th>Nazwa</th><th>Kategoria</th><th>Cena</th><th>Akcje</th></tr></thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT p.*, c.nazwa as cat FROM product p LEFT JOIN category c ON p.id_category = c.id_category ORDER BY p.id_product DESC");
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_product'] ?></td>
                        <td><?= htmlspecialchars($row['nazwa']) ?></td>
                        <td><?= htmlspecialchars($row['cat'] ?? 'Brak') ?></td>
                        <td><?= number_format($row['cena'], 2, ',', '.') ?> zł</td>
                        <td>
                            <a href="#" class="btn-small">Edytuj</a>
                            <a href="?delete_product=<?= $row['id_product'] ?>" class="btn-del" onclick="return confirm('Na pewno usunąć?')">Usuń</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- DODAJ PRODUKT -->
                <section id="add-product" class="card hidden">
            <h2>Dodaj nowy produkt</h2>
            
            <form action="" method="post" enctype="multipart/form-data" class="form-grid">
                <div class="form-row">
                    <label>Nazwa produktu</label>
                    <input type="text" name="name" required placeholder="np. T-shirt oversize">
                </div>

                <div class="form-row">
                    <label>Cena (PLN)</label>
                    <input type="number" step="0.01" name="price" required placeholder="99.99">
                </div>

                <div class="form-row">
                    <label>Kategoria</label>
                    <select name="category" required>
                        <option value="">— wybierz kategorię —</option>
                        <?php 
                        $cats = $conn->query("SELECT * FROM category ORDER BY nazwa");
                        while($c = $cats->fetch_assoc()): ?>
                            <option value="<?= $c['id_category'] ?>"><?= htmlspecialchars($c['nazwa']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-row full">
                    <label>Opis produktu</label>
                    <textarea name="description" rows="5" placeholder="Krótki opis produktu..."></textarea>
                </div>

                <div class="form-row">
                    <label>Zdjęcie produktu</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <div class="form-actions">
                    <button type="submit" name="add_product" class="btn" style="font-size:1.1rem; padding:12px 30px;">
                        Dodaj produkt
                    </button>
                </div>
            </form>
        </section>

        <!-- KATEGORIE -->
        <section id="categories" class="card hidden">
            <h2>Kategorie</h2>
            <form action="" method="post" class="category-form">
                <input type="text" name="nazwa" placeholder="Nowa kategoria" required>
                <button class="btn" name="add_category">Dodaj</button>
            </form>
            <table>
                <thead><tr><th>ID</th><th>Nazwa</th><th>Akcje</th></tr></thead>
                <tbody>
                    <?php $cats = $conn->query("SELECT * FROM category"); while($c = $cats->fetch_assoc()): ?>
                    <tr>
                        <td><?= $c['id_category'] ?></td>
                        <td><?= $c['nazwa'] ?></td>
                        <td>
                            <a href="#" class="btn-small">Edytuj</a>
                            <a href="?delete_category=<?= $c['id_category'] ?>" class="btn-del" onclick="return confirm('Usunąć?')">Usuń</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- PROMOCJE -->
      <section id="promotions" class="card hidden">
        <h2>Zarządzanie promocjami</h2>
        
        <!-- Formularz dodawania promocji -->
        <form action="" method="post" class="form-grid promo-form">
          
          <div class="form-row">
            <label>Wybierz produkt</label>
            <select name="product" required>
              <option value="">— wybierz produkt —</option>
              <?php
  $prods = $conn->query("SELECT id_product, nazwa FROM product ORDER BY nazwa");
  while($p = $prods->fetch_assoc()):
    ?>
              <option value="<?= $p['id_product'] ?>"><?= htmlspecialchars($p['nazwa']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          
          <div class="form-row">
            <label>Zniżka (%)</label>
            <input type="number" name="discount" min="1" max="90" required placeholder="np. 20">
          </div>
          
          <div class="form-row">
            <label>Data zakończenia (opcjonalnie)</label>
            <input type="date" name="end_date">
          </div>
          
          <div class="form-actions full">
            <button type="submit" name="add_promotion" class="btn">Dodaj promocję</button>
          </div>
        </form>
        
        <hr style="margin: 25px 0;">
        
        <h3>Aktywne promocje</h3>
        
        <table>
          <thead>
            <tr>
              <th>Produkt</th>
              <th>Cena normalna</th>
              <th>Cena promocyjna</th>
              <th>Zniżka</th>
              <th>Do</th>
              <th>Akcje</th>
            </tr>
          </thead>
          <tbody>
            <?php
  $promo = $conn->query("
    SELECT pp.*, p.nazwa 
    FROM produkty_w_promocji pp
    JOIN product p ON p.id_product = pp.id_product
    WHERE pp.aktywna = 1
    ORDER BY pp.data_rozpoczecia DESC
    ");
  
  while ($p = $promo->fetch_assoc()):
    $disc = round((1 - $p['cena_promocyjna'] / $p['cena_normalna']) * 100);
    ?>
            <tr>
              <td><?= htmlspecialchars($p['nazwa']) ?></td>
              <td><?= number_format($p['cena_normalna'], 2, ',', ' ') ?> zł</td>
              <td><strong style="color:#0a7;"><?= number_format($p['cena_promocyjna'], 2, ',', ' ') ?> zł</strong></td>
              <td><?= $disc ?>%</td>
              <td><?= $p['data_zakonczenia'] ? date('d.m.Y', strtotime($p['data_zakonczenia'])) : 'Bez końca' ?></td>
              <td>
                <a href="?delete_promo=<?= $p['id'] ?>" class="btn-del" onclick="return confirm('Usunąć promocję?')">Usuń</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </section>


        <!-- KLIENCI -->
        <section id="customers" class="card hidden">
            <h2>Klienci</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imię i nazwisko</th>
                        <th>Email</th>
                        <th>Zamówienia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT
                                u.id_user,
                                u.email,
                                COALESCE(
                                    CONCAT(ud.imię, ' ', ud.nazwisko),
                                    u.t_login,
                                    'Brak imienia'
                                ) AS imie_nazwisko,
                                COUNT(o.id_order) AS zamowienia
                            FROM users u
                            LEFT JOIN user_data ud ON u.id_user = ud.id_user
                            LEFT JOIN orders o ON u.id_user = o.id_user
                            WHERE u.czy_admin = 0
                            GROUP BY u.id_user
                            ORDER BY imie_nazwisko ASC";

                    $users = $conn->query($sql);
                    $lp = 1;
                    while($u = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $lp++ ?></td>
                        <td style="font-weight: 600; font-size: 1.1rem;">
                            <?= htmlspecialchars($u['imie_nazwisko']) ?>
                        </td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td style="text-align:center; font-weight: bold; color:#ffd60a;">
                            <?= $u['zamowienia'] ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<script>
document.querySelectorAll('.menu-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.card').forEach(c => c.classList.add('hidden'));
        document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
        document.getElementById(this.dataset.section).classList.remove('hidden');
        this.classList.add('active');
    });
});

function loadProducts() { }
<?php if (isset($_GET['get_products'])) {  exit(); } ?>

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.counter').forEach(box => {
        const target = parseInt(box.textContent.replace(/ /g, ''));
        let current = 0;
        const step = target / 60;
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                box.textContent = target.toLocaleString('pl-PL');
                clearInterval(timer);
            } else {
                box.textContent = Math.floor(current).toLocaleString('pl-PL');
            }
        }, 20);
    });
});
</script>
<script src="/src/js/admin.js"></script>
</body>
</html>