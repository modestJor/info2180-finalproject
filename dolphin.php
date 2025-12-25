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
    /*
    $password = $_POST['password'];
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';

    if (!preg_match($passwordRegex, $password)) {
        echo "Password does not meet complexity requirements.";
        exit();
    }
    */

    //Proceed to database verification
    $email = $_POST['username'];
    $password = $_POST['password'];

    //Query the database for the user
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    //Verify the password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        echo "success";
    } else {
        echo "invalid username or password";
    }
}
?>