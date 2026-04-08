<?php
session_start();
require "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /register-form");
    exit;
}

if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    $_SESSION['message'] = "Sessie verlopen, probeer opnieuw";
    header("Location: /register-form");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm-password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = "Ongeldig e-mailadres";
    $_SESSION['email'] = $email;
    header("Location: /register-form");
    exit;
}

if ($password !== $confirm) {
    $_SESSION['message'] = "Wachtwoorden komen niet overeen";
    $_SESSION['email'] = $email;
    header("Location: /register-form");
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['message'] = "Wachtwoord moet minimaal 6 tekens zijn";
    $_SESSION['email'] = $email;
    header("Location: /register-form");
    exit;
}

try {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO account (email, password) 
        VALUES (:email, :password)
    ");

    $stmt->execute([
        ':email' => $email,
        ':password' => $hash
    ]);

    $_SESSION['success'] = "Registratie gelukt, log in";
    header("Location: /login-form");
    exit;

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        $_SESSION['message'] = "Dit e-mailadres is al in gebruik";
    } else {
        $_SESSION['message'] = "Database fout";
    }

    $_SESSION['email'] = $email;
    header("Location: /register-form");
    exit;
}