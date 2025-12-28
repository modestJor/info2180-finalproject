<?php
session_start();

/* Block access if not logged in */
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please log in.";
    exit();
}

/* Database connection - USE db_connect.php */
require 'db_connect.php';

/* Get filter parameter */
$filter = $_GET['filter'] ?? 'all';
$userId = $_SESSION['user_id'];

/* Build query based on filter */
$query = "SELECT id, title, firstname, lastname, email, telephone, company, type, created_at, updated_at FROM Contacts";
$params = [];

if ($filter === 'sales') {
    $query .= " WHERE type = 'Sales Lead'";
} elseif ($filter === 'support') {
    $query .= " WHERE type = 'Support'";
} elseif ($filter === 'assigned') {
    $query .= " WHERE assigned_to = ?";
    $params[] = $userId;
}

$query .= " ORDER BY updated_at DESC";

/* Execute query */
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Escape helper */
function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>

<div class="dashboard">

    <!-- 🔹 TOP BAR -->
    <div class="top-bar">
        Welcome, <?= e($_SESSION['firstname'] ?? ''); ?> |
        <?php if (($_SESSION['role'] ?? '') === 'Admin'): ?>
            <a href="#" onclick="loadUsers(); return false;">Users</a> |
        <?php endif; ?>
        <a href="#" onclick="logout(); return false;">Logout</a>
    </div>

    <div class="dashboard-header">
        <h2>Dashboard</h2>
        <button class="btn-add-contact" onclick="loadNewContact()">+ Add Contact</button>
    </div>

    <!-- 🔹 FILTER BUTTONS -->
    <div class="filter-container">
        <p>Filter By:</p>
        <button class="filter-btn <?= $filter === 'all' ? 'active' : '' ?>" onclick="loadDashboard('all')">All Contacts</button>
        <button class="filter-btn <?= $filter === 'sales' ? 'active' : '' ?>" onclick="loadDashboard('sales')">Sales Leads</button>
        <button class="filter-btn <?= $filter === 'support' ? 'active' : '' ?>" onclick="loadDashboard('support')">Support</button>
        <button class="filter-btn <?= $filter === 'assigned' ? 'active' : '' ?>" onclick="loadDashboard('assigned')">Assigned to me</button>
    </div>

    <h3>Contacts</h3>

    <table class="contacts-table" width="100%" cellspacing="0" cellpadding="8" border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Company</th>
                <th>Type</th>
                <th>Updated</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($contacts) === 0): ?>
                <tr>
                    <td colspan="7">No contacts found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= e($contact['title'] . '. ' . $contact['firstname'] . ' ' . $contact['lastname']) ?></td>
                        <td><?= e($contact['email']) ?></td>
                        <td><?= e($contact['telephone'] ?: '—') ?></td>
                        <td><?= e($contact['company']) ?></td>
                        <td>
                            <span class="type-badge <?= strtolower(str_replace(' ', '-', $contact['type'])) ?>">
                                <?= e($contact['type']) ?>
                            </span>
                        </td>
                        <td><?= e($contact['updated_at']) ?></td>
                        <td>
                            <button onclick="loadContactDetails(<?= (int)$contact['id'] ?>)">View</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
