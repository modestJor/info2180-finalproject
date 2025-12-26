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

$userId = (int)$_SESSION['user_id'];
$contactId = filter_input(INPUT_POST, 'contact_id', FILTER_VALIDATE_INT);
$comment = trim($_POST['comment'] ?? '');

if (!$contactId || $comment === '') {
    http_response_code(400);
    echo json_encode(["ok" => false, "message" => "Contact and comment are required"]);
    exit;
}

try {
    $pdo->beginTransaction();

    // Insert note
    $stmt = $pdo->prepare("INSERT INTO Notes (contact_id, comment, created_by) VALUES (?, ?, ?)");
    $stmt->execute([$contactId, $comment, $userId]);

    // Update contact updated_at (spec requirement)
    $upd = $pdo->prepare("UPDATE Contacts SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $upd->execute([$contactId]);

    $pdo->commit();

    echo json_encode(["ok" => true, "message" => "Note added"]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => "Server error adding note"]);
}
