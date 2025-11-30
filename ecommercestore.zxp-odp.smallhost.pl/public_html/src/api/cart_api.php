<?php
session_start();
require_once '../api/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("status" => "error", "message" => "Nie zalogowany"));
    exit;
}

$user_id = $_SESSION['user_id'];
$input   = json_decode(file_get_contents("php://input"), true);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === "add") {
    $id  = (int)(isset($input['id']) ? $input['id'] : 0);
    $qty = (int)(isset($input['quantity']) ? $input['quantity'] : 1);
    $stmt = $conn->prepare("SELECT ilosc FROM koszyk WHERE id_user = ? AND id_product = ?");
    $stmt->bind_param("ii", $user_id, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $conn->query("UPDATE koszyk SET ilosc = ilosc + $qty WHERE id_user = $user_id AND id_product = $id");
    } else {
        $stmt2 = $conn->prepare("INSERT INTO koszyk (id_user, id_product, ilosc) VALUES (?, ?, ?)");
        $stmt2->bind_param("iii", $user_id, $id, $qty);
        $stmt2->execute();
    }

    echo json_encode(array("status" => "success"));
    exit;
}
?>
