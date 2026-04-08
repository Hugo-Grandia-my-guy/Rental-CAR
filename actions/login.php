<?php
session_start();
require "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /login-form");
    exit;
}

if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    $_SESSION['error'] = "Sessie verlopen, probeer opnieuw";
    header("Location: /login-form");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

try {
    $stmt = $pdo->prepare("
        SELECT id, password 
        FROM account 
        WHERE email = :email 
        LIMIT 1
    ");

    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $email;

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header("Location: /");
        exit;
    }

    $_SESSION['error'] = "E-mail of wachtwoord is incorrect";
    $_SESSION['email'] = $email;
    header("Location: /login-form");
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = "Database fout";
    header("Location: /login-form");
    exit;
}