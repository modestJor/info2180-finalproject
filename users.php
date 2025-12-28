<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "<h2>Access denied</h2><p>Please log in.</p>";
    exit;
}

if (($_SESSION['role'] ?? '') !== 'Admin') {
    http_response_code(403);
    echo "<h2>Access denied</h2><p>Admins only.</p>";
    exit;
}

$stmt = $pdo->prepare("
  SELECT firstname, lastname, email, role, created_at
  FROM users
  ORDER BY created_at DESC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<div class="users-page">
  <div style="text-align:right; margin-bottom:15px;">
    Welcome, <?= e($_SESSION['firstname'] ?? ''); ?> |
    <a href="#" onclick="loadDashboard(); return false;">Dashboard</a> |
    <a href="logout.php">Logout</a>
  </div>

  <h2>Users</h2>

  <table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>Name</th><th>Email</th><th>Role</th><th>Created</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($users) === 0): ?>
        <tr><td colspan="4">No users found.</td></tr>
      <?php else: foreach ($users as $u): ?>
        <tr>
          <td><?= e($u['firstname'].' '.$u['lastname']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['role']) ?></td>
          <td><?= e($u['created_at']) ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
