<?php
session_start();
require '../api/db.php';

$conn->set_charset("utf8mb4");
mysqli_set_charset($conn, "utf8mb4");
$conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

$error = '';
$success = '';
$fullname = $email = $login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm-password'] ?? '';

    if (empty($fullname) || empty($email) || empty($login) || empty($password) || empty($confirm)) {
        $error = "Wypełnij wszystkie pola!";
    }
    elseif (strlen($fullname) < 3) {
        $error = "Imię i nazwisko musi mieć co najmniej 3 znaki.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Podaj poprawny adres e-mail!";
    }
    elseif (strlen($login) < 3) {
        $error = "Login musi mieć co najmniej 3 znaki.";
    }
    elseif (!preg_match('/^[a-zA-Z0-9._-]+$/', $login)) {
        $error = "Login może zawierać tylko litery, cyfry, kropki, myślniki i podkreślenia.";
    }
    elseif (strlen($password) < 6) {
        $error = "Hasło musi mieć co najmniej 6 znaków!";
    }
    elseif ($password !== $confirm) {
        $error = "Hasła nie są takie same!";
    }
    else {
      
        $stmt = $conn->prepare("SELECT id_user FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Ten adres e-mail jest już zarejestrowany!";
            $stmt->close();
        }
        else {
            $stmt->close();

            $stmt = $conn->prepare("SELECT id_user FROM users WHERE t_login = ? LIMIT 1");
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = "Ten login jest już zajęty! Wybierz inny.";
                $stmt->close();
            }
            else {
                $stmt->close();

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $email_latin1     = mb_convert_encoding($email,           'latin1', 'utf-8');
                $hashed_latin1    = mb_convert_encoding($hashed_password, 'latin1', 'utf-8');
                $login_latin1     = mb_convert_encoding($login,           'latin1', 'utf-8');
                $szczegoly        = "Zarejestrowany: " . date('d.m.Y H:i') . " | $fullname";
                $szczegoly_latin1 = mb_convert_encoding($szczegoly,       'latin1', 'utf-8');

                $insert = $conn->prepare("
                    INSERT INTO users (email, hasło, t_login, czy_admin, szczegoly)
                    VALUES (?, ?, ?, 0, ?)
                ");
                $insert->bind_param("ssss", $email_latin1, $hashed_latin1, $login_latin1, $szczegoly_latin1);

                if ($insert->execute()) {
                    $success = "
                        Konto zostało utworzone pomyślnie!<br><br>
                        <strong>Twój login:</strong> <span style='color:#27ae60; font-size:1.3em;'>$login</span><br>
                        <strong>E-mail:</strong> $email<br><br>
                        Teraz możesz się <a href='login.php' style='color:#2980b9; text-decoration:underline; font-weight:600;'>zalogować</a>
                    ";

                } else {
                    $error = "Błąd serwera podczas rejestracji. Spróbuj ponownie później.";
                }
                $insert->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja – Sklep Internetowy</title>
    <link rel="stylesheet" href="/src/assets/styles/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .success, .error {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            font-weight: 500;
        }
        .success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
        .error   { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
        input[name="login"] { font-family: monospace; }
    </style>
</head>
<body>

    <?php require('../components/header.php'); ?>

    <section class="auth-section">
        <div class="auth-card">
            <h2>Załóż konto</h2>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form action="" method="post">
                <input type="text" name="fullname" placeholder="Imię i nazwisko" required
                       value="<?= htmlspecialchars($fullname ?? '') ?>" autocomplete="name">

                <input type="email" name="email" placeholder="Adres e-mail" required
                       value="<?= htmlspecialchars($email ?? '') ?>" autocomplete="username">

                <input type="text" name="login" placeholder="Login (twój własny)" required minlength="3"
                       value="<?= htmlspecialchars($login ?? '') ?>" autocomplete="username"
                       pattern="[a-zA-Z0-9._-]+" title="Tylko litery, cyfry, ._-" style="font-family:monospace;">

                <input type="password" name="password" placeholder="Hasło (min. 6 znaków)" required
                       minlength="6" autocomplete="new-password">

                <input type="password" name="confirm-password" placeholder="Powtórz hasło" required
                       autocomplete="new-password">

                <button type="submit">Zarejestruj się</button>
            </form>
            <?php endif; ?>

            <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
        </div>
    </section>

    <?php require('../components/footer.php'); ?>
    <script src="/src/assets/js/cart.js"></script>
</body>
</html>