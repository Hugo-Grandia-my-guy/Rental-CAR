<?php
session_start();
require 'database/connection.php';

if (!isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF token invalid');
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

function getUserFromDb($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$user = getUserFromDb($email);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['success'] = "Welkom!";

    session_write_close();
    header("Location: /");
    exit;
} else {
    $_SESSION['error'] = "E-mail of wachtwoord is incorrect";
    $_SESSION['email'] = $email;
    header("Location: /login");
    exit;
}