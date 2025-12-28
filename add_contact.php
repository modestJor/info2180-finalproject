<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["ok" => false, "message" => "Not logged in"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["ok" => false, "message" => "Invalid request"]);
    exit;
}

require 'db_connect.php';

// Validate inputs
$title = trim($_POST['title'] ?? '');
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$company = trim($_POST['company'] ?? '');
$type = trim($_POST['type'] ?? '');
$assigned_to = filter_input(INPUT_POST, 'assigned_to', FILTER_VALIDATE_INT) ?: null;
$created_by = (int)$_SESSION['user_id'];

// Basic validation
if (empty($title) || empty($firstname) || empty($lastname) || empty($email) || empty($type)) {
    http_response_code(400);
    echo json_encode(["ok" => false, "message" => "Title, first name, last name, email, and type are required"]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["ok" => false, "message" => "Invalid email format"]);
    exit;
}

try {
    // Check if email already exists
    $checkStmt = $pdo->prepare("SELECT id FROM Contacts WHERE email = ?");
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        http_response_code(400);
        echo json_encode(["ok" => false, "message" => "Email already exists in contacts"]);
        exit;
    }
    
    // Insert contact
    $stmt = $pdo->prepare("
        INSERT INTO Contacts 
        (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
    ");
    
    $stmt->execute([$title, $firstname, $lastname, $email, $telephone, $company, $type, $assigned_to, $created_by]);
    
    echo json_encode(["ok" => true, "message" => "Contact added successfully"]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => "Server error adding contact"]);
}
?>