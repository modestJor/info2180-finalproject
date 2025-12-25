<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method";
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo "Access denied. Only administrators can add users.";
    exit;
}

require 'db_connect.php';

// Validate inputs
$fname = trim($_POST['firstname'] ?? '');
$lname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'Member';

// Basic validation
if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
    echo "All fields are required";
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format";
    exit;
}

// Validate password strength (same regex as in dolphin.php)
$passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
if (!preg_match($passwordRegex, $password)) {
    echo "Password must be at least 8 characters with uppercase, lowercase, and a number";
    exit;
}

// Check if email already exists
$checkStmt = $pdo->prepare("SELECT id FROM Users WHERE email = ?");
$checkStmt->execute([$email]);
if ($checkStmt->fetch()) {
    echo "Email already exists";
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $pdo->prepare("INSERT INTO Users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
if ($stmt->execute([$fname, $lname, $email, $hashedPassword, $role])) {
    echo "User added successfully";
} else {
    echo "Error adding user to database";
}
?>