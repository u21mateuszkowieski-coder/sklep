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
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8">
    <title>Dziękujemy!</title>
  </head>
  <body style="text-align:center;padding:100px;font-family:sans-serif;" data-logged="true" data-user-id="<?= $_SESSION['user_id'] ?>">
<h1 style="color:#ff6b35;">Dziękujemy za zamówienie!</h1>
<p>Numer Twojego zamówienia: <strong>#<?= htmlspecialchars($_GET['order']) ?></strong></p>
<p>Na maila wysłaliśmy potwierdzenie.</p>
<br><a href="client-index.php" style="color:#ff6b35;font-size:1.2em;">← Wróć na stronę główną</a>
<script src="/src/js/cart.js"></script>
</body>
</html>
