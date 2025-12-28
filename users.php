<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<h2>Access Denied</h2><p>Only administrators can view users.</p>";
    exit;
}

require 'db_connect.php';

$stmt = $pdo->prepare("
    SELECT id, firstname, lastname, email, role, created_at 
    FROM Users 
    ORDER BY created_at DESC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<div class="dashboard-controls">
    <h2>Users</h2>
    <button class="btn-back" onclick="loadDashboard()">← Back to Dashboard</button>
    <button class="btn-add" onclick="loadNewUser()">+ Add User</button>
</div>

<table class="contacts-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><strong><?= e($user['firstname'] . ' ' . $user['lastname']) ?></strong></td>
            <td><?= e($user['email']) ?></td>
            <td><?= e($user['role']) ?></td>
            <td><?= e($user['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>