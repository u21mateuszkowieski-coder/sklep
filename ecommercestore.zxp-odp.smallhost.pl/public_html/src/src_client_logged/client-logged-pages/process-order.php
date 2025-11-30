<?php
session_start();

/* --- 1. SPRAWDZENIE SESJI --- */
if (!isset($_SESSION['logged'], $_SESSION['user_id'], $_SESSION['username']) ||
    (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1)) {

    header("Location: /public/index.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/api/db.php';
$user_id = $_SESSION['user_id'];

/* --- 2. POBIERANIE KOSZYKA --- */
$stmt = $conn->prepare("SELECT id_product, ilosc FROM koszyk WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($items)) {
    header("Location: client-cart.php");
    exit();
}

/* --- FUNKCJA DIAGNOSTYCZNA --- */
function fail($stage, $conn, $stmt = null) {
    $error = $stmt ? $stmt->error : $conn->error;
    $conn->rollback();

    // Czytelny komunikat
    die("<h3>❌ Błąd na etapie: <b>$stage</b></h3>
         <p>🔍 MySQL: <b>$error</b></p>");
}

/* --- 3. TWORZENIE ZAMÓWIENIA W TRANSAKCJI --- */
$conn->autocommit(false);
$conn->begin_transaction();

try {

    /* --- 3.1 Dodawanie zamówienia --- */
    $stmt = $conn->prepare("INSERT INTO orders (id_user, total_price, created_at, status)
                            VALUES (?, 100, NOW(), 'pending')");
    if (!$stmt) fail("prepare orders", $conn);

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) fail("execute orders", $conn, $stmt);

    $order_id = $conn->insert_id;
    $stmt->close();


    /* --- 3.2 Wstawienie pozycji zamówienia --- */
    $stmt = $conn->prepare("
        INSERT INTO order_items (id_order, id_product, quantity, price_per_unit)
        SELECT ?, id_product, quantity,
            (SELECT cena FROM product WHERE id_product = koszyk.id_product)
        FROM koszyk WHERE id_user = ?
    ");

    if (!$stmt) fail("prepare order_items", $conn);

    $stmt->bind_param("ii", $order_id, $user_id);
    if (!$stmt->execute()) fail("execute order_items", $conn, $stmt);

    $stmt->close();


    /* --- 3.3 Czyszczenie koszyka --- */
    $stmt = $conn->prepare("DELETE FROM koszyk WHERE id_user = ?");
    if (!$stmt) fail("prepare delete koszyk", $conn);

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) fail("execute delete koszyk", $conn, $stmt);

    $stmt->close();


    /* --- 3.4 Zatwierdzamy transakcję --- */
    $conn->commit();

} catch (Exception $e) {
    fail("wyjątek PHP", $conn);
}

/* --- 4. MAIL DO KLIENTA --- */
$to = "twoj@mail.pl"; // TODO: pobierz z bazy klienta
$subject = "Zamówienie #$order_id przyjęte!";
$message = "Dziękujemy za zakupy w EcommerceStore!\n\nTwoje zamówienie nr $order_id zostało przyjęte.\nWkrótce otrzymasz kolejną wiadomość z linkiem do płatności.";

mail($to, $subject, $message);

/* --- 5. PRZEKIEROWANIE --- */
header("Location: order-success.php?order=$order_id");
exit();
?>
