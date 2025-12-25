<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please log in.";
    exit();
}

$host = 'localhost';
$db   = 'dolphin_crm';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>