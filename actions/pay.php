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

unset($_SESSION['csrf_token']);

$carId = filter_input(INPUT_POST, 'car_id', FILTER_VALIDATE_INT);
$days  = filter_input(INPUT_POST, 'days', FILTER_VALIDATE_INT);

if (!$carId || !$days || $days < 1) {
    die("Ongeldige invoer");
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT kosten FROM auto WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $carId]);

    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$car) {
        throw new Exception("Auto niet gevonden");
    }

    $total = (float)$car['kosten'] * $days;

    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, car_id, days, total_price)
        VALUES (:user, :car, :days, :total)
    ");

    $stmt->execute([
        ':user'  => $_SESSION['id'],
        ':car'   => $carId,
        ':days'  => $days,
        ':total' => $total
    ]);

    $pdo->commit();

    $_SESSION['success'] = "Betaling gelukt!";
    header("Location: /");
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Database fout: " . $e->getMessage());
}