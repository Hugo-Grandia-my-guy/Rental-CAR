<?php
session_start();
require "../database/connection.php";

if (!isset($_SESSION['id'])) {
    header("Location: /login-form");
    exit;
}

if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    die("CSRF fout");
}

$carId = filter_input(INPUT_POST, 'car_id', FILTER_VALIDATE_INT);
$days = filter_input(INPUT_POST, 'days', FILTER_VALIDATE_INT);

if (!$carId || !$days || $days < 1) {
    die("Ongeldige invoer");
}

try {
    $stmt = $pdo->prepare("SELECT kosten FROM auto WHERE id = :id");
    $stmt->execute([':id' => $carId]);

    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Auto niet gevonden");
    }

    $total = $car['kosten'] * $days;

    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, car_id, days, total_price)
        VALUES (:user, :car, :days, :total)
    ");

    $stmt->execute([
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