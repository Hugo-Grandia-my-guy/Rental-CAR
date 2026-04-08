<?php
session_start();
require "../database/connection.php";

if (!isset($_SESSION['id'])) {
    header("Location: /login-form");
    exit;
}

$carId = $_POST['car_id'] ?? null;
$days = (int)($_POST['days'] ?? 1);

if (!$carId || $days < 1) {
    die("Ongeldige data");
}

try {
    $stmt = $pdo->prepare("SELECT kosten FROM auto WHERE id = :id");
    $stmt->execute([':id' => $carId]);

    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Auto niet gevonden");
    }

    $total = $car['kosten'] * $days;

    $insert = $pdo->prepare("
        INSERT INTO orders (user_id, car_id, days, total_price)
        VALUES (:user, :car, :days, :total)
    ");

    $insert->execute([
        ':user' => $_SESSION['id'],
        ':car' => $carId,
        ':days' => $days,
        ':total' => $total
    ]);

    $_SESSION['success'] = "Betaling gelukt!";
    header("Location: /");
    exit;

} catch (PDOException $e) {
    die("Database fout");
}