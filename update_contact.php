<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];
    $userId = $_SESSION['user_id'];

    if ($action === 'assign') {
        $stmt = $pdo->prepare("UPDATE Contacts SET assigned_to = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$userId, $id]);
    } elseif ($action === 'switch') {
        // Toggle type between Sales Lead and Support
        $stmt = $pdo->prepare("UPDATE Contacts SET type = IF(type='Sales Lead', 'Support', 'Sales Lead'), updated_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    }
    echo "success";
}
?>