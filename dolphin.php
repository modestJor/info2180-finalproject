<?php
session_start();

$host = 'localhost';
$db   = 'dolphin_crm';
$dbuser = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $dbuser, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // FIXED: Changed "users" to "Users" to match schema
    $stmt = $pdo->prepare("SELECT id, firstname, lastname, email, password, role FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userRow && password_verify($password, $userRow['password'])) {

        
        $_SESSION['user_id']   = $userRow['id'];
        $_SESSION['role']      = $userRow['role'];        // Admin or Member
        $_SESSION['firstname'] = $userRow['firstname'];
        $_SESSION['lastname']  = $userRow['lastname'];

        echo "success";
        exit;
    }

    echo "fail";
    exit;
}
?>
