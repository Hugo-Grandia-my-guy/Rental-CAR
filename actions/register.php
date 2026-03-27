<?php
session_start();
require_once __DIR__ . "/../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

$email = filter_var($_POST["email"] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST["password"] ?? '';
$confirm_password = $_POST["confirm-password"] ?? '';

if (!$email) {
    $_SESSION["message"] = "Ongeldig e-mailadres.";
    header("Location: /register-form");
    exit();
}

if (strlen($password) < 6) {
    $_SESSION["message"] = "Wachtwoord moet minimaal 6 tekens zijn.";
    header("Location: /register-form");
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION["message"] = "Wachtwoorden komen niet overeen.";
    $_SESSION["email"] = htmlspecialchars($email);
    header("Location: /register-form");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id FROM account WHERE email = :email LIMIT 1");
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetch()) {
        $_SESSION["message"] = "Dit e-mailadres is al in gebruik.";
        $_SESSION["email"] = htmlspecialchars($email);
        header("Location: /register-form");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);

    $stmt = $pdo->prepare("
        INSERT INTO account (email, password)
        VALUES (:email, :password)
    ");

    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->bindValue(":password", $hashedPassword, PDO::PARAM_STR);
    $stmt->execute();

    $_SESSION["success"] = "Registratie gelukt! Log nu in.";
    header("Location: /login-form");
    exit();

} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}