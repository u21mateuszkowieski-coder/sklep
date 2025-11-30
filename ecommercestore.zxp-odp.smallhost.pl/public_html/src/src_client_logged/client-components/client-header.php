<?php session_start(); ?>
<body
    data-logged="<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>"
    data-user-id="<?= $_SESSION['user_id'] ?? '' ?>">

<header class="site-header">
  <div class="header-container">
    <div class="logo">
      <h1>EcommerceStore</h1>
    </div>
    
    <div class="search-bar">
      <input type="text" placeholder="Szukaj produktów...">
      <button><img src="/src/assets/icon/search.svg" alt="Szukaj"></button>
    </div>
    
    <div class="header-icons">
      <a href="../client-logged-pages/client-cart.php"><img src="/src/assets/icon/shopping-cart.svg" alt="Koszyk"></a>
      <a href="../client-logged-pages/dashboard.php"><img src="/src/assets/icon/user.svg" alt="Konto"></a>
      <a href="../../../public/index.php" class="logout-btn">Wyloguj</a>
    </div>
  </div>
  
  <nav class="main-nav">
    <ul>
      <li><a href="../client-logged-pages/client-index.php">Strona główna</a></li>
      <li><a href="../client-logged-pages/client-products.php">Produkty</a></li>
      <li><a href="../client-logged-pages/client-promoted.php">Promocje</a></li>
      <li><a href="../client-logged-pages/client-new-products.php">Nowości</a></li>
      <li><a href="../client-logged-pages/client-contact.php">Kontakt</a></li>
    </ul>
  </nav>
</header>
