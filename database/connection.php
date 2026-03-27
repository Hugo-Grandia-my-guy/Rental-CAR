<?php
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;dbname=rental;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true,
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}