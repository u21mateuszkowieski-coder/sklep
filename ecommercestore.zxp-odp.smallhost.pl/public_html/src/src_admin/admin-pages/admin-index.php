<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admina — Sklep</title>
  <link rel="stylesheet" href="/src/src_admin/admin-assets/admin-styles/admin-style.css">
</head>
<body>
  <header class="site-header">
    <div class="header-container">
      <div class="logo">
        <h1>Panel Admina</h1>
      </div>
      <div class="header-actions">
        <a href="/public/index.html"><button id="logoutBtn" class="logout-btn">Wyloguj się</button></a>
      </div>
    </div>
  </header>

  <div class="admin-wrap">
    <!-- SIDEBAR -->
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

    <!-- MAIN -->
    <main class="main">
      <section id="dashboard" class="card visible">
        <h2>Podsumowanie</h2>
        <div class="muted">Najważniejsze dane sklepu</div>
        <div class="grid3">
          <div class="info-box"><p>Produkty</p><h3>128</h3></div>
          <div class="info-box"><p>Promocje</p><h3>7</h3></div>
          <div class="info-box"><p>Użytkownicy</p><h3>1 342</h3></div>
        </div>
      </section>

      <section id="products" class="card hidden">
        <h2>Produkty</h2>
        <table>
          <thead>
            <tr><th>#</th><th>Nazwa</th><th>Kategoria</th><th>Cena</th><th>Akcje</th></tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td><td>Słuchawki TWS</td><td>Elektronika</td><td>249,00 zł</td>
              <td><a href="#" class="btn-small">Edytuj</a> <a href="#" class="btn-del">Usuń</a></td>
            </tr>
          </tbody>
        </table>
      </section>

      <section id="add-product" class="card hidden">
        <h2>Dodaj / Edytuj produkt</h2>
        <form action="/admin/products" method="post" enctype="multipart/form-data" class="form-grid">
          <div class="form-row">
            <label>Nazwa</label>
            <input type="text" name="name" placeholder="np. Laptop Dell">
          </div>

          <div class="form-row">
            <label>Cena (PLN)</label>
            <input type="number" name="price" step="0.01" placeholder="np. 2499.00">
          </div>

          <div class="form-row">
            <label>Kategoria</label>
            <select name="category">
              <option>Elektronika</option>
              <option>Moda</option>
            </select>
          </div>

          <div class="form-row full">
            <label>Opis</label>
            <textarea name="description" placeholder="Opis produktu..."></textarea>
          </div>

          <div class="form-row">
            <label>Zdjęcie produktu</label>
            <input type="file" id="productImage" name="image" accept="image/*">
          </div>

          <div id="imagePreview" class="image-preview"></div>

          <div class="form-actions">
            <button class="btn">Zapisz</button>
            <button type="reset" class="btn-outline">Wyczyść</button>
          </div>
        </form>
      </section>

      <section id="categories" class="card hidden">
        <h2>Kategorie</h2>
        <form class="category-form">
          <input type="text" placeholder="Nowa kategoria">
          <button class="btn">Dodaj</button>
        </form>
        <table>
          <thead><tr><th>#</th><th>Nazwa</th><th>Akcje</th></tr></thead>
          <tbody>
            <tr><td>1</td><td>Elektronika</td><td><a href="#" class="btn-small">Edytuj</a> <a href="#" class="btn-del">Usuń</a></td></tr>
          </tbody>
        </table>
      </section>

      <section id="promotions" class="card hidden">
        <h2>Promocje</h2>
        <form class="promo-form form-grid">
          <div class="form-row">
            <label>Kategoria</label>
            <select id="promoCategory" name="category">
              <option value="">— wybierz kategorię —</option>
              <option value="elektronika">Elektronika</option>
              <option value="moda">Moda</option>
              <option value="dom">Dom</option>
            </select>
          </div>

          <div class="form-row">
            <label>Produkt</label>
            <select id="promoProduct" name="product" disabled>
              <option value="">— najpierw wybierz kategorię —</option>
            </select>
          </div>

          <div class="form-row">
            <label>Zniżka (%)</label>
            <input type="number" name="discount" placeholder="np. 15" min="1" max="90">
          </div>

          <div class="form-row">
            <label>Data zakończenia promocji</label>
            <input type="date" name="end_date">
          </div>

          <div class="form-actions">
            <button class="btn">Ustaw promocję</button>
          </div>
        </form>
      </section>

      <section id="customers" class="card hidden">
        <h2>Klienci</h2>
        <table>
          <thead><tr><th>#</th><th>Imię</th><th>Email</th><th>Zamówienia</th></tr></thead>
          <tbody>
            <tr><td>1</td><td>Jan Kowalski</td><td>jan@example.com</td><td>2</td></tr>
          </tbody>
        </table>
      </section>
    </main>
  </div>

  <script src="/src/js/admin.js"></script>
</body>
</html>
