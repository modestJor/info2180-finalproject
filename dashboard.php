<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please log in.";
    exit();
}

require 'db_connect.php';

$filter = $_GET['filter'] ?? 'all';
$userId = $_SESSION['user_id'];


$query = "SELECT id, title, firstname, lastname, email, telephone, company, type FROM Contacts";

if ($filter === 'sales') {
    $query .= " WHERE type = 'Sales Lead'";
} elseif ($filter === 'support') {
    $query .= " WHERE type = 'Support'";
} elseif ($filter === 'assigned') {
    $query .= " WHERE assigned_to = :userId";
}



$stmt = $pdo->prepare($query);
if ($filter === 'assigned') {
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
}

$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>


<div class="dashboard-controls">
    <h2>Dashboard</h2>
    <div>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
        <button class="btn-users" onclick="loadUsers()">View Users</button>
        <?php endif; ?>
        <button class="btn-add" onclick="loadNewContact()">+ Add Contact</button>
        <button class="btn-logout" onclick="logout()">Logout</button>
    </div>
</div>

<div class="filter-container">
    <p>Filter By:</p>
    <button onclick="loadDashboard('all')">All</button>
    <button onclick="loadDashboard('sales')">Sales Leads</button>
    <button onclick="loadDashboard('support')">Support</button>
    <button onclick="loadDashboard('assigned')">Assigned to me</button>
</div>

<table class="contacts-table">
    <thead>
        <tr><th>Name</th><th>Email</th><th>Telephone</th><th>Company</th><th>Type</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php foreach ($contacts as $contact): ?>
        <tr>
            <td><strong><?= e($contact['title'] . ' ' . $contact['firstname'] . ' ' . $contact['lastname']) ?></strong></td>
            <td><?= e($contact['email']) ?></td>
            <td><?= e($contact['telephone']) ?></td> 
            <td><?= e($contact['company']) ?></td>
            <td class="type-pill <?= strtolower(str_replace(' ', '-', $contact['type'])) ?>"><?= e($contact['type']) ?></td>
            <td><a href="#" onclick="loadContactDetails(<?= $contact['id'] ?>)">View</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
