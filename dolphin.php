<?php
session_start();

$host = 'localhost';
$db   = 'dolphin_crm';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Special case for initial admin login (password123)
    $email = $_POST['username'];
    $password = $_POST['password'];
    
    // Query the database for the user
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // Special handling for admin@project2.com with password123
    if ($email === 'admin@project2.com' && $password === 'password123') {
        // Verify if this is the initial password (hashed version in DB)
        if ($user && password_verify('password123', $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            echo "success";
        } else {
            echo "invalid username or password";
        }
    } else {
        // For all other logins, use normal password verification
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            echo "success";
        } else {
            echo "invalid username or password";
        }
    }
}
?>