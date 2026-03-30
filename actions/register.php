<?php
session_start();
require "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /register-form");
    exit;
}

if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    $_SESSION['message'] = "Sessie verlopen, probeer opnieuw";
    header("Location: /register-form");
    exit;
}

$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$confirm_password = $_POST['confirm-password'];

if ($password !== $confirm_password) {
    $_SESSION['message'] = "Wachtwoorden komen niet overeen.";
    $_SESSION['email'] = htmlspecialchars($email);
    header("Location: /register-form");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM account WHERE email = :email");
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['message'] = "Dit e-mailadres is al in gebruik.";
        $_SESSION['email'] = htmlspecialchars($email);
        header("Location: /register-form");
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $insert = $pdo->prepare("INSERT INTO account (email, password) VALUES (:email, :password)");
    $insert->bindValue(":email", $email, PDO::PARAM_STR);
    $insert->bindValue(":password", $hash, PDO::PARAM_STR);
    $insert->execute();

    $_SESSION['success'] = "Registratie is gelukt, log nu in";
    header("Location: /login-form");
    exit;

} catch (PDOException $e) {
    $_SESSION['message'] = "Database fout: " . $e->getMessage();
    header("Location: /register-form");
    exit;
}