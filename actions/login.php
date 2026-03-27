<?php
session_start();
require_once __DIR__ . "/../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

if (empty($_POST['email']) || empty($_POST['password'])) {
    die('Vul alle velden in.');
}

try {
    $stmt = $pdo->prepare("SELECT * FROM account WHERE email = :email LIMIT 1");
    $stmt->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die('Gebruiker niet gevonden.');
    }

    if (!password_verify($_POST['password'], $user['password'])) {
        die('Onjuist wachtwoord.');
    }

    $_SESSION['id'] = $user['id'];
    $_SESSION['email'] = $user['email'];

    header('Location: /');
    exit;

} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}