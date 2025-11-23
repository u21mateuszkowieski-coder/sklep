<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Logowanie – Sklep Internetowy</title>
  <link rel="stylesheet" href="/src/assets/styles/login.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php require('../components/header.php'); ?>

  <section class="auth-section">
    <div class="auth-card">
      <h2>Zaloguj się</h2>
      <form action="#" method="post">
        <input type="email" name="email" placeholder="Adres e-mail" required />
        <input type="password" name="password" placeholder="Hasło" required />
        <button type="submit">Zaloguj się</button>
      </form>
      <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
    </div>
  </section>

    <?php require('../components/footer.php'); ?>

    <script src="/src/assets/js/cart.js"></script>
</body>
</html>
